<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\ProviderSlotMapping;
use App\Http\Requests\UserRequest;
use App\Models\ProviderPayout;
use App\Models\ProviderSubscription;
use App\Models\PaymentGateway;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Hash;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\CommissionEarning;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminApproveEmail;
use App\Models\ProviderLocation;
use Illuminate\Support\Facades\Validator;

class ProviderLocationController extends Controller
{
    /**
     * Update provider's current location
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProviderLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        try {
            // Find or create provider location record
            $providerLocation = ProviderLocation::updateOrCreate(
                ['provider_id' => $request->provider_id],
                [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'last_updated' => now(),
                ]
            );

            return comman_custom_response([
                'status' => true,
                'message' => __('messages.location_update')
            ]);
        } catch (\Exception $e) {
            return comman_custom_response([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}