<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorAuthController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
        $this->generateSecret();

    }

    // Show the form to enable 2FA
    public function showEnableForm()
    {
        $user = auth()->user();
        // dd($user->two_factor_secret);
        // Generate the URL for the QR code
        $QR_Code_Url = $this->google2fa->getQRCodeUrl(
            config('app.name'), // Your application name
            $user->email, // User's email (or username)
            $user->two_factor_secret // The secret key for the user
        );
    
        // Use the QR Code URL to generate the image
        $QR_Image = QrCode::size(200)->generate($QR_Code_Url); // You can adjust the size as needed
    
        return view('auth.twofactor.enable', compact('QR_Image'));
    }

    // Enable 2FA for the user
    public function enable(Request $request)
    {
        $user = auth()->user();

        // Validate the OTP code
        $validated = $this->google2fa->verifyKey($user->two_factor_secret, $request->input('otp'));

        if ($validated) {
            $user->two_factor_enabled = true;
            $user->save();
            return redirect()->route('dashboard')->with('success', '2FA enabled successfully');
        }

        return back()->with('error', 'Invalid OTP code');
    }

    // Disable 2FA
    public function disable()
    {
        $user = auth()->user();
        $user->two_factor_enabled = false;
        $user->save();

        return redirect()->route('dashboard')->with('success', '2FA disabled successfully');
    }

    // Generate 2FA Secret for the user
    public function generateSecret()
    {
        $user = auth()->user();
        dd($user);
        $google2fa = app('pragmarx.google2fa');

        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = $secret;
        $user->save();

        return response()->json(['secret' => $secret]);
    }
}
