<h2>Scan QR Code with Google Authenticator</h2>
<img src="{{ $qrCodeUrl }}" alt="QR Code">
<form method="POST" action="{{ route('2fa.enable') }}">
    @csrf
    <input name="code" placeholder="Enter OTP">
    <button type="submit">Enable 2FA</button>
</form>
