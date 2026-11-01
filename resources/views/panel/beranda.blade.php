<h1>Panel Staf</h1>
<p>Halo, {{ auth()->user()->nama }} ({{ auth()->user()->peran }})</p>

<form method="POST" action="{{ route('keluar') }}">
    @csrf
    <button type="submit">Keluar</button>
</form>