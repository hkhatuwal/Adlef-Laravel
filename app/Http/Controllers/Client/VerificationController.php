<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{

    public function showVerifyForm(){
        return view('client.auth.verify');
    }

    public function showDocumentPending(){
        return view('client.auth.document-verification-pending');
    }

    public function sendEmailOtp(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->contactDetails) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            // Generate 6 digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            Log::info($otp);

            // Store OTP in session with expiry time (15 minutes)
            session([
                'email_otp' => [
                    'code' => $otp,
                    'expires_at' => now()->addMinutes(15),
                    'email' => $user->contactDetails->email
                ]
            ]);

            // Send OTP email
            Mail::to($user->contactDetails->email)->send(new OtpVerification($otp));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP '.$e
            ], 500);
        }
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $otpData = session('email_otp');

        if (!$otpData || now()->isAfter($otpData['expires_at'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired'
            ], 400);
        }

        if ($request->otp !== $otpData['code']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Clear OTP from session
        session()->forget('email_otp');

        // Update user verification status
        $user = auth()->user();
        $user->contactDetails->is_email_verified =true;
        // temporarily
        $user->contactDetails->is_phone_verified =true;

        $user->contactDetails->save();
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully'
        ]);
    }

    public function sendPhoneOtp(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->contactDetails) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            // Generate 6 digit OTP
            $otp = "123456";
//            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in session with expiry time (15 minutes)
            session([
                'phone_otp' => [
                    'code' => $otp,
                    'expires_at' => now()->addMinutes(15),
                    'phone' => $user->contactDetails->phone
                ]
            ]);
            Log::info("User otp for verification ".$otp);

            // Here you would integrate with an SMS service to send the OTP
            // For now, we'll just return success
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP'
            ], 500);
        }
    }

    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $otpData = session('phone_otp');

        if (!$otpData || now()->isAfter($otpData['expires_at'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired'
            ], 400);
        }

        if ($request->otp !== $otpData['code']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Clear OTP from session
        session()->forget('phone_otp');

        // Update user verification status
        $user = auth()->user();
        $user->contactDetails->is_phone_verified =true;
        $user->contactDetails->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Phone verified successfully'
        ]);
    }
    public function verifyDocument(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $user = auth()->user();
        $user->profile->document_path = $request->path;
        $user->profile->document_verified = false; // Will be verified by admin
        $user->profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Document send for verification'
        ]);
    }

}

