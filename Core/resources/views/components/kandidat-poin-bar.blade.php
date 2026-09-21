@props(['value' => 0])

@php
    $warna = $value > 80 ? '#10b981' : ($value >= 50 ? '#fbbf24' : '#f43f5e');
    $lebar = min(100, max(0, $value));
@endphp

<div class="kp-poinbar">
    <div class="track"><div class="fill" style="width: {{ $lebar }}%; background: {{ $warna }};"></div></div>
    <span class="num">{{ $value }}</span>
</div>