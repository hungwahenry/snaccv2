@extends('emails.layout')

@section('title', 'Your SNACC Login Code')

@section('content')
    <h2 style="color: #111827; margin-top: 0;">Your Login Code</h2>

    <p>Hello!</p>

    <p>You requested to sign in to SNACC. Use the code below to complete your login:</p>

    <div class="otp-code">
        {{ $otp }}
    </div>

    <div class="info-box">
        <strong>Important:</strong> This code will expire in 10 minutes and can only be used once.
    </div>

    <p>If you didn't request this code, you can safely ignore this email.</p>

    <p>
        Thanks,<br>
        The SNACC Team
    </p>
@endsection
