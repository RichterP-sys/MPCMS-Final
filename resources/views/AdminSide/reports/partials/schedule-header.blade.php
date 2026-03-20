@php
    $coopName = config('cooperative.name', config('app.name', 'MPCMS'));
    $coopSubtitle = config('cooperative.subtitle', 'Davao del Sur State College');
    $coopAddress = config('cooperative.address', '');
@endphp
<div class="text-center mb-4">
    <h2 class="text-lg font-bold text-slate-900 uppercase">{{ $coopName }}</h2>
    <p class="text-sm text-slate-600">{{ $coopSubtitle }}</p>
    @if($coopAddress)
    <p class="text-xs text-slate-500">{{ $coopAddress }}</p>
    @endif
</div>
