<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two-Factor Challenge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Two-Factor Authentication</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div>
            <label for="code">Authentication Code</label>
            <input id="code" type="text" name="code" required autofocus autocomplete="one-time-code" />
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember_device" />
                Remember this device for 15 days
            </label>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
