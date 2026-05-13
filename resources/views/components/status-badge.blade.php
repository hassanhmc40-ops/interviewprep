@props(['status'])

@php
    $classes = match($status) {
        'to_review' => 'bg-slate-100 text-slate-700 border-slate-200',
        'in_progress' => 'bg-amber-100 text-amber-700 border-amber-200',
        'mastered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = match($status) {
        'to_review' => 'To Review',
        'in_progress' => 'In Progress',
        'mastered' => 'Mastered',
        default => $status,
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $classes }}">
    {{ $label }}
</span>