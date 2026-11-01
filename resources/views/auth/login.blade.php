<h1>Login Staf</h1>

@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('login.proses') }}">
    @csrf
    <div><input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required></div>
    <div><input type="password" name="password" placeholder="Password" required></div>
    <div><label><input type="checkbox" name="ingat"> Ingat saya</label></div>
    <button type="submit">Masuk</button>
</form>