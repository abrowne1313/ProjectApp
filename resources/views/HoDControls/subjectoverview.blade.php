@extends('layouts.default')


@section('title', 'HoD Subject Overview')

@section('content')
{{-- Admin subject selector --}}
@if(in_array(auth()->user()->user_type, [1, 2]))
    <form method="GET" class="mb-4">
        <label for="subject_id" class="form-label fw-bold">
            Select Subject
        </label>

        <select
            name="subject_id"
            id="subject_id"
            class="form-select"
            onchange="this.form.submit()"
        >
            @foreach ($subjects as $subj)
                <option
                    value="{{ $subj->id }}"
                    {{ $activeSubject->id === $subj->id ? 'selected' : '' }}
                >
                    {{ $subj->Subject }}
                </option>
            @endforeach
        </select>
    </form>
@endif
@if (!$isAdmin && $subjects->count() > 1)
<ul class="nav nav-tabs mb-3 subject-tabs">
    @foreach ($subjects as $subj)
        <li class="nav-tabs.subject-item">
            <a
                class="nav-link subject-tab {{ $activeSubject->id === $subj->id ? 'active' : '' }}"
                href="{{ route('subject.overview', ['subject_id' => $subj->id]) }}"
            >
                {{ $subj->Subject }}
            </a>
        </li>
    @endforeach
</ul>
@endif
    <h1>{{ $activeSubject->Subject }} Department Topic Overview</h1>

<a href="{{ route('schemes.create') }}" class="btn-primary">
    Create New Scheme
</a>
<hr class="my-4">

<h2 class="mb-3">Existing Schemes for {{ $activeSubject->Subject }}</h2>

@if ($schemes->count() === 0)
    <p class="text-muted">No schemes have been created yet for this subject.</p>
@else
    <div class="list-group">

        @foreach ($schemes as $scheme)
        <li>
            <a
                href="{{ route('schemes.show', $scheme->id) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            >
                <span>
                    <strong>Year {{ $scheme->YearGroup }} {{ $activeSubject->Subject }}</strong>
                </span>

                <span class="badge bg-primary rounded-pill">
                    
                </span>
            </a>
</li>
        @endforeach

    </div>
@endif

@endsection
 