<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\User;
use App\Models\Service;
use App\Models\ProviderAddressMapping;
use App\Models\BookingActivity;
use App\Models\Payment;
use App\Models\HandymanRating;
use App\Models\BookingRating;
use App\Models\ProviderSlot;
use App\Models\ProviderLocation;
use App\Http\Resources\API\BookingResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmergencyBookingController extends Controller
{
    public function createEmergencyBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'provider_id' => 'required|exists:users,id',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $user_id = auth()->user()->id;
        $service = Service::find($request->service_id);
        
        if (!$service) {
            return response()->json(['message' => __('messages.service_not_found'), 'status' => 0]);
        }

        $booking_data = [
            'service_id' => $request->service_id,
            'service_name' => $service->name,
            'provider_id' => $request->provider_id,
            'user_id' => $user_id,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'status' => $request->status ?? 'pending',
            'payment_id' => null,
            'address' => $request->address ?? null,
            'latitude' => $request->location['latitude'] ?? null,
            'longitude' => $request->location['longitude'] ?? null,
            'is_emergency' => 1,
            'description' => $request->description ?? __('messages.emergency_service_request'),
            'price' => $service->price,
            'quantity' => 1,
            'discount' => 0,
            'total_amount' => $service->price,
            'booking_address_id' => $request->booking_address_id ?? null,
        ];

        $booking = Booking::create($booking_data);

        // Create booking activity
        $activity_data = [
            'booking_id' => $booking->id,
            'activity_type' => 'create_booking',
            'activity_message' => __('messages.emergency_booking_created'),
            'activity_data' => json_encode(['id' => $booking->id, 'is_emergency' => 1]),
            'activity_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        
        BookingActivity::create($activity_data);

        // Send notification to provider
        $provider = User::find($request->provider_id);
        $user = auth()->user();
        
        if ($provider && $provider->fcm_token) {
            $notification_data = [
                'id' => $booking->id,
                'type' => 'emergency_booking',
                'message' => __('messages.emergency_booking_notification', ['user' => $user->display_name]),
                'service_name' => $service->name,
            ];
            
            sendNotification('emergency_booking', $notification_data, $provider->fcm_token);
        }

        return response()->json([
            'status' => 1,
            'message' => __('messages.emergency_booking_created_successfully'),
            'bookingDetail' => new BookingResource($booking),
        ]);
    }

    public function findNearbyProviders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius; // in kilometers
        $category_id = $request->category_id;

        // Find providers who offer services in this category and are available
        $providers = User::where('user_type', 'provider')
            ->where('status', 1)
            ->where('is_available', 1)
            ->whereHas('providerService', function($q) use ($category_id) {
                $q->where('category_id', $category_id);
            })
            ->get();

        $nearby_providers = [];

        foreach ($providers as $provider) {
            // First check real-time location if available (within last 30 minutes)
            $provider_location = ProviderLocation::where('provider_id', $provider->id)
                ->where('last_updated', '>=', now()->subMinutes(30))
                ->first();
            
            // If no real-time location, fall back to provider's registered address
            if (!$provider_location) {
                $provider_address = ProviderAddressMapping::where('provider_id', $provider->id)
                    ->where('status', 1)
                    ->first();
                
                if ($provider_address) {
                    $provider_location = (object)[
                        'latitude' => $provider_address->latitude,
                        'longitude' => $provider_address->longitude
                    ];
                }
            }

            if ($provider_location) {
                // Calculate distance
                $distance = $this->calculateDistance(
                    $latitude, 
                    $longitude, 
                    $provider_location->latitude, 
                    $provider_location->longitude
                );

                // If within radius
                if ($distance <= $radius) {
                    $provider->distance = round($distance, 2);
                    
                    // Add provider rating
                    $provider->rating = $provider->providerRating ? $provider->providerRating->avg('rating') : 0;
                    
                    $nearby_providers[] = $provider;
                }
            }
        }

        // Sort by combination of distance and rating
        usort($nearby_providers, function($a, $b) {
            // Give more weight to rating than distance
            $a_score = ($a->rating * 2) - ($a->distance * 0.1);
            $b_score = ($b->rating * 2) - ($b->distance * 0.1);
            
            return $b_score <=> $a_score; // Higher score first
        });

        return response()->json(['data' => $nearby_providers, 'status' => 1]);
    }

    public function emergencyBookingAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:bookings,id',
            'status' => 'required|in:accept,reject',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $booking = Booking::find($request->id);
        
        if (!$booking) {
            return response()->json(['message' => __('messages.booking_not_found'), 'status' => 0]);
        }

        // Check if this is provider's booking
        if ($booking->provider_id != auth()->user()->id) {
            return response()->json(['message' => __('messages.not_authorized'), 'status' => 0]);
        }

        $booking->status = $request->status == 'accept' ? 'accept' : 'cancelled';
        $booking->save();

        // Create booking activity
        $activity_data = [
            'booking_id' => $booking->id,
            'activity_type' => 'update_status',
            'activity_message' => $request->status == 'accept' ? 
                __('messages.emergency_booking_accepted') : 
                __('messages.emergency_booking_rejected'),
            'activity_data' => json_encode(['id' => $booking->id, 'status' => $booking->status]),
            'activity_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        
        BookingActivity::create($activity_data);

        // Send notification to user
        $user = User::find($booking->user_id);
        $provider = auth()->user();
        
        if ($user && $user->fcm_token) {
            $notification_data = [
                'id' => $booking->id,
                'type' => 'emergency_booking_status',
                'message' => $request->status == 'accept' ? 
                    __('messages.emergency_booking_accepted_notification', ['provider' => $provider->display_name]) : 
                    __('messages.emergency_booking_rejected_notification', ['provider' => $provider->display_name]),
            ];
            
            sendNotification('emergency_booking_status', $notification_data, $user->fcm_token);
        }

        return response()->json([
            'status' => 1,
            'message' => $request->status == 'accept' ? 
                __('messages.emergency_booking_accepted_successfully') : 
                __('messages.emergency_booking_rejected_successfully'),
            'bookingDetail' => new BookingResource($booking),
        ]);
    }

    public function getEmergencyBookingList(Request $request)
    {
        $user = auth()->user();
        $per_page = $request->per_page ?? 10;
        
        $bookings = Booking::where('is_emergency', 1);
        
        if ($user->user_type == 'provider') {
            $bookings = $bookings->where('provider_id', $user->id);
        } elseif ($user->user_type == 'user') {
            $bookings = $bookings->where('user_id', $user->id);
        }
        
        $bookings = $bookings->orderBy('created_at', 'desc')->paginate($per_page);
        
        return response()->json([
            'status' => 1,
            'message' => __('messages.list_fetched_successfully'),
            'data' => BookingResource::collection($bookings),
            'pagination' => [
                'total_items' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'total_pages' => $bookings->lastPage(),
            ],
        ]);
    }

    // Helper function to calculate distance between two coordinates using Haversine formula
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c; // Distance in km
        
        return $distance;
    }

    /**
     * Handle emergency booking request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleEmergencyBooking(Request $request)
    {
        try {
            $booking = Booking::findOrFail($request->id);
            $booking->status = $request->status;
            $booking->save();
            
            // Get user data
            $user = User::findOrFail($booking->customer_id);
            
            // Prepare notification data
            $notificationData = [
                'id' => $booking->id,
                'type' => 'emergency_booking_status',
                'subject' => 'Emergency Booking Status',
                'message' => $request->status == 'accept' ? 
                    'Your emergency request has been accepted' : 
                    'Your emergency request has been declined',
                'notification-type' => 'booking'
            ];
            
            sendNotification('user', $user, $notificationData);
            
            return comman_custom_response([
                'status' => true,
                'message' => 'Emergency booking ' . ($request->status == 'accept' ? 'accepted' : 'declined') . ' successfully'
            ]);
        } catch (\Exception $e) {
            return comman_custom_response([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get all emergency bookings for admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminEmergencyBookingList(Request $request)
    {
        // Verify admin access
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'status' => 0,
                'message' => __('messages.not_authorized')
            ], 403);
        }
        
        $per_page = $request->per_page ?? 10;
        
        $bookings = Booking::where('is_emergency', 1)
            ->with('customer', 'provider', 'service', 'payment');
        
        // Apply filters if provided
        if ($request->has('status') && $request->status != '') {
            $bookings->where('status', $request->status);
        }
        
        if ($request->has('provider_id') && $request->provider_id != '') {
            $bookings->where('provider_id', $request->provider_id);
        }
        
        if ($request->has('customer_id') && $request->customer_id != '') {
            $bookings->where('user_id', $request->customer_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $bookings->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $bookings->whereDate('created_at', '<=', $request->date_to);
        }
        
        $bookings = $bookings->orderBy('created_at', 'desc')->paginate($per_page);
        
        return response()->json([
            'status' => 1,
            'message' => __('messages.list_fetched_successfully'),
            'data' => BookingResource::collection($bookings),
            'pagination' => [
                'total_items' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'total_pages' => $bookings->lastPage(),
            ],
        ]);
    }

    /**
     * Admin action on emergency booking
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminEmergencyBookingAction(Request $request)
    {
        // Verify admin access
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'status' => 0,
                'message' => __('messages.not_authorized')
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:bookings,id',
            'status' => 'required|in:pending,accept,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $booking = Booking::find($request->id);
        
        if (!$booking) {
            return response()->json([
                'status' => 0,
                'message' => __('messages.booking_not_found')
            ]);
        }

        if (!$booking->is_emergency) {
            return response()->json([
                'status' => 0,
                'message' => __('messages.not_emergency_booking')
            ]);
        }

        $booking->status = $request->status;
        $booking->save();

        // Create booking activity
        $activity_data = [
            'booking_id' => $booking->id,
            'activity_type' => 'update_status',
            'activity_message' => __('messages.emergency_booking_status_updated_by_admin'),
            'activity_data' => json_encode(['id' => $booking->id, 'status' => $booking->status]),
            'activity_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        
        BookingActivity::create($activity_data);

        // Notify customer and provider
        $this->notifyEmergencyStatusChange($booking);
        
        return response()->json([
            'status' => 1,
            'message' => __('messages.emergency_booking_updated_successfully'),
            'bookingDetail' => new BookingResource($booking)
        ]);
    }

    /**
     * Send notifications for emergency booking status change
     * 
     * @param Booking $booking
     */
    private function notifyEmergencyStatusChange($booking)
    {
        // Notify customer
        $user = User::find($booking->user_id);
        if ($user && $user->fcm_token) {
            $customerNotification = [
                'id' => $booking->id,
                'type' => 'emergency_booking_status',
                'message' => __('messages.emergency_booking_status_updated_by_admin', ['status' => $booking->status]),
            ];
            
            sendNotification('emergency_booking_status', $customerNotification, $user->fcm_token);
        }
        
        // Notify provider
        $provider = User::find($booking->provider_id);
        if ($provider && $provider->fcm_token) {
            $providerNotification = [
                'id' => $booking->id,
                'type' => 'emergency_booking_status',
                'message' => __('messages.emergency_booking_status_updated_by_admin', ['status' => $booking->status]),
            ];
            
            sendNotification('emergency_booking_status', $providerNotification, $provider->fcm_token);
        }
    }

    /**
     * Get emergency booking statistics for admin dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function emergencyBookingStats()
    {
        // Verify admin access
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'status' => 0,
                'message' => __('messages.not_authorized')
            ], 403);
        }
        
        $totalEmergencyBookings = Booking::where('is_emergency', 1)->count();
        $pendingEmergencyBookings = Booking::where('is_emergency', 1)->where('status', 'pending')->count();
        $completedEmergencyBookings = Booking::where('is_emergency', 1)->where('status', 'completed')->count();
        $cancelledEmergencyBookings = Booking::where('is_emergency', 1)->where('status', 'cancelled')->count();
        
        $recentEmergencyBookings = Booking::where('is_emergency', 1)
            ->with('customer', 'provider', 'service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return response()->json([
            'status' => 1,
            'data' => [
                'total' => $totalEmergencyBookings,
                'pending' => $pendingEmergencyBookings,
                'completed' => $completedEmergencyBookings,
                'cancelled' => $cancelledEmergencyBookings,
                'recent' => BookingResource::collection($recentEmergencyBookings)
            ]
        ]);
    }
}



