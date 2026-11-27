<h1>Panel Staf</h1>
<p>Halo, {{ auth()->user()->nama }} ({{ auth()->user()->peran }})</p>

<form method="POST" action="{{ route('keluar') }}"
      data-confirm="Apakah Anda yakin ingin keluar dari panel ini?"
      data-confirm-title="Keluar dari Panel?"
      data-confirm-btn="Ya, Keluar"
      data-confirm-type="danger">
    @csrf
    <button type="submit">Keluar dari halaman ini</button>
</form>