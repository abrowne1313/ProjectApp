@extends('layouts.default')

@section('title', 'HoD Subject Overview')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-dark mb-1">{{ $activeSubject->Subject }} Department</h1>
            <p class="text-muted">Manage schemes of work and revision topics</p>
        </div>
        <a href="{{ route('schemes.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Create New Scheme
        </a>
    </div>

    {{-- Subject Selector: Only visible for Admin (1) or HoD (2) --}}
    @if(in_array(auth()->user()->user_type, [1, 2]))
    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body">
            <form method="GET" action="{{ route('subject.overview') }}" class="row align-items-end">
                <div class="col-md-4">
                    <label for="subject_id" class="form-label fw-bold text-secondary small uppercase">
                        Switch Department View
                    </label>
                    <select 
                        name="subject_id" 
                        id="subject_id" 
                        class="form-select border-0 shadow-sm" 
                        onchange="this.form.submit()"
                    >
                        @foreach ($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ $activeSubject->id === $subj->id ? 'selected' : '' }}>
                                {{ $subj->Subject }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
@endif
    <hr class="my-4 opacity-25">

    <h2 class="h4 mb-3">Existing Schemes</h2>

    @if ($schemes->isEmpty())
        <div class="text-center py-5 bg-white rounded shadow-sm">
            <i class="bi bi-journal-x display-1 text-light"></i>
            <p class="text-muted mt-3">No schemes have been created yet for {{ $activeSubject->Subject }}.</p>
        </div>
    @else
        <div class="col-md-6 col-lg-4 mb-5">
            @foreach ($schemes as $scheme)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('schemes.show', $scheme->id) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm scheme-card">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="h5 mb-1 text-dark">Year {{ $scheme->YearGroup }}</h3>
                                    <span class="text-muted small">{{ $activeSubject->Subject }}</span>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-2 rounded">
                           
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .scheme-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .scheme-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
        border-left: 4px solid #0d6efd !important;
    }
    .form-label.uppercase {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
    }
</style>
@endsection