<h1>
    @php
    $now = new DateTime();
    @endphp
    {{ $now->format('Y-m-d H:i:s') }}
</h1>