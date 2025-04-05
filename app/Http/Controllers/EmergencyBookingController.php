<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\ProviderAddressMapping;
use App\Traits\NotificationTrait;
use App\Traits\EarningTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmergencyBookingController extends Controller
{
    use NotificationTrait;
    use EarningTrait;

    /**
     * Create a new emergency booking
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createEmergencyBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:users,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        try {
            $booking = new Booking();
            $booking->service_id = $request->service_id;
            $booking->customer_id = $request->customer_id;
            $booking->date = Carbon::now();
            $booking->status = 'pending';
            $booking->description = $request->description;
            $booking->latitude = $request->latitude;
            $booking->longitude = $request->longitude;
            $booking->is_emergency = 1;
            $booking->save();

            // Trigger notification for emergency booking
            $activity_data = [
                'activity_type' => 'emergency_booking_created',
                'booking_id' => $booking->id,
                'booking' => $booking,
            ];
            $this->sendNotification($activity_data);

            return comman_custom_response([
                'message' => __('messages.emergency_booking_created'),
                'booking_id' => $booking->id,
                'status' => true
            ]);
        } catch (\Exception $e) {
            return comman_custom_response([
                'message' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Find nearby providers for emergency service
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findNearbyProviders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'service_id' => 'required|exists:services,id',
            'radius' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $radius = $request->radius ?? 10; // Default 10 km radius
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $service_id = $request->service_id;

        // Find providers who offer this service and are within radius
        $providers = User::role('provider')
            ->where('status', 1)
            ->whereHas('providerService', function($q) use($service_id) {
                $q->where('service_id', $service_id);
            })
            ->select('id', 'display_name', 'email', 'contact_number', 'latitude', 'longitude')
            ->selectRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        return comman_custom_response([
            'providers' => $providers,
            'count' => $providers->count(),
            'status' => true
        ]);
    }

    /**
     * Handle provider action on emergency booking
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function emergencyBookingAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'provider_id' => 'required|exists:users,id',
            'action' => 'required|in:accept,reject',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $booking = Booking::find($request->booking_id);
        
        if (!$booking->is_emergency) {
            return comman_custom_response(['message' => __('messages.not_emergency_booking'), 'status' => false]);
        }

        if ($request->action == 'accept') {
            $booking->provider_id = $request->provider_id;
            $booking->status = 'accept';
            $booking->save();

            // Notify customer that provider accepted
            $activity_data = [
                'activity_type' => 'emergency_booking_accepted',
                'booking_id' => $booking->id,
                'booking' => $booking,
            ];
            $this->sendNotification($activity_data);

            return comman_custom_response(['message' => __('messages.emergency_booking_accepted'), 'status' => true]);
        } else {
            // If rejected, just log the rejection but don't change booking status
            // This allows other providers to still accept it
            return comman_custom_response(['message' => __('messages.emergency_booking_rejected'), 'status' => true]);
        }
    }

    /**
     * Get emergency booking list for provider or customer
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getEmergencyBookingList(Request $request)
    {
        $user = auth()->user();
        $bookings = Booking::where('is_emergency', 1);

        if ($user->hasRole('provider')) {
            $bookings = $bookings->where('provider_id', $user->id);
        } elseif ($user->hasRole('user')) {
            $bookings = $bookings->where('customer_id', $user->id);
        }

        $bookings = $bookings->with('customer', 'provider', 'service', 'payment')
            ->orderBy('created_at', 'desc')
            ->get();

        return comman_custom_response(['bookings' => $bookings, 'status' => true]);
    }

    /**
     * Handle emergency booking (complete, cancel, etc.)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleEmergencyBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'status' => 'required|in:completed,cancelled,in_progress',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $booking = Booking::find($request->booking_id);
        
        if (!$booking->is_emergency) {
            return comman_custom_response(['message' => __('messages.not_emergency_booking'), 'status' => false]);
        }

        $booking->status = $request->status;
        $booking->save();

        // Notify about status change
        $activity_data = [
            'activity_type' => 'emergency_booking_' . $request->status,
            'booking_id' => $booking->id,
            'booking' => $booking,
        ];
        $this->sendNotification($activity_data);

        return comman_custom_response(['message' => __('messages.booking_status_updated'), 'status' => true]);
    }

    /**
     * Get admin emergency booking list
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminEmergencyBookingList(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole(['admin', 'demo_admin'])) {
            return comman_custom_response(['message' => __('messages.unauthorized'), 'status' => false], 403);
        }

        $bookings = Booking::where('is_emergency', 1)
            ->with('customer', 'provider', 'service', 'payment')
            ->orderBy('created_at', 'desc')
            ->get();

        return comman_custom_response(['bookings' => $bookings, 'status' => true]);
    }

    /**
     * Admin action on emergency booking
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminEmergencyBookingAction(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole(['admin', 'demo_admin'])) {
            return comman_custom_response(['message' => __('messages.unauthorized'), 'status' => false], 403);
        }

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'status' => 'required|in:accept,cancelled',
            'provider_id' => 'required_if:status,accept|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $booking = Booking::find($request->booking_id);
        
        if (!$booking->is_emergency) {
            return comman_custom_response(['message' => __('messages.not_emergency_booking'), 'status' => false]);
        }

        $booking->status = $request->status;
        
        if ($request->status == 'accept' && $request->provider_id) {
            $booking->provider_id = $request->provider_id;
        }
        
        $booking->save();

        // Notify about admin action
        $activity_data = [
            'activity_type' => 'admin_emergency_booking_' . $request->status,
            'booking_id' => $booking->id,
            'booking' => $booking,
        ];
        $this->sendNotification($activity_data);

        return comman_custom_response(['message' => __('messages.booking_status_updated'), 'status' => true]);
    }

    /**
     * Get emergency booking statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function emergencyBookingStats(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole(['admin', 'demo_admin'])) {
            return comman_custom_response(['message' => __('messages.unauthorized'), 'status' => false], 403);
        }

        $stats = [
            'total' => Booking::where('is_emergency', 1)->count(),
            'pending' => Booking::where('is_emergency', 1)->where('status', 'pending')->count(),
            'accepted' => Booking::where('is_emergency', 1)->where('status', 'accept')->count(),
            'completed' => Booking::where('is_emergency', 1)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('is_emergency', 1)->where('status', 'cancelled')->count(),
        ];

        return comman_custom_response(['stats' => $stats, 'status' => true]);
    }

    /**
     * Display emergency bookings web interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function emergencyBookings()
    {
        $pageTitle = __('messages.emergency_bookings');
        $auth_user = authSession();
        $assets = ['datatable'];
        
        return view('emergency-booking.index', compact('pageTitle', 'auth_user', 'assets'));
    }

    /**
     * Get emergency bookings data for datatable.
     *
     * @param \Yajra\DataTables\DataTables $datatable
     * @return \Illuminate\Http\JsonResponse
     */
    public function emergencyBookingsData(\Yajra\DataTables\DataTables $datatable)
    {
        $query = Booking::where('is_emergency', 1)
            ->with('customer', 'provider', 'service', 'payment');
        
        if (auth()->user()->hasRole('provider')) {
            $query = $query->where('provider_id', auth()->user()->id);
        } elseif (auth()->user()->hasRole('user')) {
            $query = $query->where('customer_id', auth()->user()->id);
        }
        
        return $datatable->eloquent($query)
            ->addColumn('customer_name', function (Booking $booking) {
                return optional($booking->customer)->display_name ?? '-';
            })
            ->addColumn('provider_name', function (Booking $booking) {
                return optional($booking->provider)->display_name ?? '-';
            })
            ->addColumn('service_name', function (Booking $booking) {
                return optional($booking->service)->name ?? '-';
            })
            ->editColumn('status', function (Booking $booking) {
                return '<span class="badge bg-' . getStatusBadgeClass($booking->status) . '">' . ucfirst($booking->status) . '</span>';
            })
            ->editColumn('date', function (Booking $booking) {
                return Carbon::parse($booking->date)->format('d M Y');
            })
            ->addColumn('action', function (Booking $booking) {
                return view('emergency-booking.action', compact('booking'))->render();
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    /**
     * Show the emergency booking details.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Booking::with('customer', 'provider', 'service', 'payment')
            ->where('id', $id)
            ->where('is_emergency', 1)
            ->firstOrFail();
        
        $pageTitle = __('messages.emergency_booking_details');
        $auth_user = authSession();
        
        return view('emergency-booking.view', compact('booking', 'pageTitle', 'auth_user'));
    }
}
