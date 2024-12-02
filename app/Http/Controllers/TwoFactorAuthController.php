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
    }

    // Show the form to enable 2FA
    public function showEnableForm()
    {
        $user = auth()->user();
        $QR_Image = null;
        if ($user->two_factor_secret = "") {
            $QR_Image = $this->genrateQR();
        }
        
        return view('auth.twofactor.enable', compact('QR_Image'));
    }
    public function genrateQR()
    {
        $user = auth()->user();
        $this->generateSecret();
        $QR_Code_Url = $this->google2fa->getQRCodeUrl(
            config('app.name'), // Your application name
            $user->email, // User's email (or username)
            $user->two_factor_secret // The secret key for the user
        );
        $QR_Image = QrCode::size(200)->generate($QR_Code_Url);
        return $QR_Image;
    }
    public function newOTP()
    {
        $user = auth()->user();
        $user->two_factor_secret = "";
        $user->save();
        $QR_Image = $this->genrateQR();
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
            return redirect()->route('teacher.marks')->with('success', '2FA enabled successfully');
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

        $google2fa = app('pragmarx.google2fa');

        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = $secret;
        $user->save();

        return response()->json(['secret' => $secret]);
    }
}
