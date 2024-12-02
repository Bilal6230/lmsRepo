@extends('layouts.signin_page')

@section('content')
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            /* background: linear-gradient(to right, #6a11cb, #2575fc); */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .login-image {
            width: 100%;
            height: 100%;
            position: fixed;
            background-image: url('public/assets/images/login.png');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }

        .two-factor-form {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            text-align: center;
        }

        .two-factor-form label {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        .two-factor-form input {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .two-factor-form button {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .two-factor-form button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 91, 187, 0.5);
        }

        .qr-code {
            margin: 20px auto;
            max-width: 300px;
            display: block;
        }

        .form-logo {
            margin-bottom: 0px !important;
        }

        .form-logo img {
            height: 60px;
            margin-bottom: 20px !important;
        }

        .col-lg-6 {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .newOTP {
            float: inline-end;
            position: relative;
            top: -18px;
        }
    </style>

    <div class="row h-100">
        <div class="col-lg-12 d-flex align-items-center justify-content-center">
            <div class="two-factor-form">
                <div class="form-logo">
                    <img src="{{ asset('assets/uploads/logo/' . get_settings('dark_logo')) }}" alt="Logo">
                </div>
                <div class="qr-code">
                    {{ $QR_Image }}
                </div>
                <form action="{{ route('two-factor.enable') }}" method="POST">
                    @csrf
                    <label for="otp">Enter OTP from Google Authenticator:</label>
                    <input type="text" name="otp" id="otp" placeholder="Enter 6-digit OTP" required>
                    @if ($QR_Image == null)
                        <a href="{{ route('new.opt') }}" class="newOTP">Need new OTP?</a>
                    @endif
                    <button type="submit">Enable 2FA</button>
                </form>
            </div>
        </div>
    </div>
@endsection
