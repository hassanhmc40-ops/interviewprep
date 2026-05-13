@props(['percentage' => 0, 'color' => 'blue'])

@php
    $colorClasses = match($color) {
        'blue' => 'bg-blue-500',
        'green' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'red' => 'bg-red-500',
        default => 'bg-blue-500',
    };
@endphp

<div class="w-full">
    <div class="flex justify-between items-center mb-1">
        <span class="text-xs font-medium text-slate-600">Progress</span>
        <span class="text-xs font-medium text-slate-600">{{ $percentage }}%</span>
    </div>
    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
        <div class="h-full {{ $colorClasses }} rounded-full transition-all duration-500 ease-out" style="width: {{ $percentage }}%"></div>
    </div>
</div>