@extends('emails.layout')

@section('title', 'your magic code ✨')

@section('content')
    <p style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 24px; text-align: center;">heyyy! here's your magic code ✨</p>

    <div class="otp-code">
        {{ $otp }}
    </div>

    <p class="info-text">expires in 10 minutes • single use only</p>

    <p style="text-align: center; color: #9ca3af; font-size: 14px; margin-top: 32px;">
        didn't request this? just ignore this email.
    </p>
@endsection
