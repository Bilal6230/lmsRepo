@extends('layouts.signin_page')

@section('content')

<style type="text/css">
    .login-image {
        width: inherit; 
        height: 100%; 
        position: fixed; 
        background-image: url('public/assets/images/login.png'); 
        background-size: cover; 
        background-position: center;
    }

    /* Style for the QR code and OTP form */
    .two-factor-form {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        padding-top: 100px; /* Adjust the padding as needed */
        text-align: center;
        background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
        padding: 20px;
        border-radius: 8px;
    }

    .two-factor-form label {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .two-factor-form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .two-factor-form button {
        width: 100%;
        padding: 12px;
        font-size: 1.2rem;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
    }

    .two-factor-form button:hover {
        background-color: #0056b3;
    }

    /* QR code styling */
    .qr-code {
        margin-bottom: 20px;
        max-width: 250px;
        margin: 0 auto;
    }
</style>

<div class="row h-100">
    <div class="col-lg-6 d-none d-lg-block p-0 h-100">
        <div class="bg-image login-image">
        </div>
    </div>
    <div class="col-lg-6 p-0 h-100 position-relative">
        <div class="parent-elem">
            <div class="middle-elem">
                <div class="primary-form">
                    <div class="form-logo mb-5">
                        <img height="60px" src="{{ asset('assets/uploads/logo/'.get_settings('dark_logo')) }}">
                    </div>
                    <div class="two-factor-form">
                        <form action="{{ route('two-factor.enable') }}" method="POST">
                            @csrf
                            <label for="otp">Enter OTP from Google Authenticator:</label>
                            <input type="text" name="otp" required>

                            <button type="submit">Enable 2FA</button>
                        </form>

                        <div class="qr-code">
                            {{ $QR_Image }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
