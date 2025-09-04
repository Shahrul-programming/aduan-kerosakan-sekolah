<!DOCTYPE html>
<html>
<head>
    <title>CSRF Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>CSRF Debug Test</h1>
    
    <div>
        <h3>Session Info:</h3>
        <p>Session ID: {{ session()->getId() }}</p>
        <p>CSRF Token: {{ csrf_token() }}</p>
        <p>App URL: {{ config('app.url') }}</p>
        <p>Session Driver: {{ config('session.driver') }}</p>
        <p>Session Domain: {{ config('session.domain') }}</p>
    </div>
    
    <hr>
    
    <h3>Test CSRF Form:</h3>
    <form method="POST" action="/test-csrf">
        @csrf
        <button type="submit">Test CSRF</button>
    </form>
    
    <hr>
    
    <h3>Login Form Test:</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" value="admin@demo.com" required>
        <input type="password" name="password" value="password" required>
        <button type="submit">Test Login</button>
    </form>
    
    @if($errors->any())
        <div style="color: red;">
            <h4>Errors:</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('status'))
        <div style="color: green;">
            <p>{{ session('status') }}</p>
        </div>
    @endif
</body>
</html>
