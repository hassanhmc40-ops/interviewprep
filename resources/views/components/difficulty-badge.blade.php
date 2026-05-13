@props(['difficulty'])

@php
    $classes = match($difficulty) {
        'junior' => 'bg-blue-100 text-blue-700 border-blue-200',
        'mid' => 'bg-purple-100 text-purple-700 border-purple-200',
        'senior' => 'bg-orange-100 text-orange-700 border-orange-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = match($difficulty) {
        'junior' => 'Junior',
        'mid' => 'Mid',
        'senior' => 'Senior',
        default => $difficulty,
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $classes }}">
    {{ $label }}
</span>