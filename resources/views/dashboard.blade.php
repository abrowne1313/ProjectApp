@extends('layouts.default')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">Dashboard</h1>

    <div class="nav nav-pills mb-4">
        <a href="{{ route('dashboard', ['tab' => 'classes']) }}"
           class="nav-link {{ request('tab') === 'classes' || !request('tab') ? 'active' : '' }}">
            My Classes
        </a>

        <a href="{{ route('dashboard', ['tab' => 'schemes']) }}"
           class="nav-link {{ request('tab') === 'schemes' ? 'active' : '' }}">
            Schemes
        </a>

        @if(auth()->user()->user_type == 3)
            <a href="{{ route('dashboard', ['tab' => 'departmentalClasses']) }}"
               class="nav-link {{ request('tab') === 'departmentalClasses' ? 'active' : '' }}">
                Departmental Classes
            </a>
        @endif
    </div>

    @if(request('tab') === 'classes' || !request('tab'))
        <div>
            <h2 class="h4 mb-3">Your Assigned Classes</h2>

            @if ($classes->isEmpty())
                <div class="text-center py-5 bg-white rounded shadow-sm border border-light">
                    <h4 class="text-muted">No Classes Assigned</h4>
                    <p class="text-muted mb-0">Please contact your administrator to be assigned to a class.</p>
                </div>
            @else
                <div class="row g-4"> 
                    @foreach ($classes as $class)
                    <h1> </h1>
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('class.pupils', $class->id) }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm scheme-card">
                                    <div class="card-body p-4">
                                        <h3 class="h5 mb-1 text-dark">{{ $class->ClassName }}</h3>
                                        <span class="text-muted small">{{ $class->Subject }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if(request('tab') === 'schemes')
        <div>
            <h2 class="h4 mb-3">Select a Subject to View Schemes</h2>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="col-md-4">
                        <input type="hidden" name="tab" value="schemes">
                        <select name="subject_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">Choose a subject...</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->Subject }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if(isset($schemes) && $selectedSubject)
                <h3 class="h5 mb-4 text-secondary">Schemes for {{ $selectedSubject->Subject }}</h3>

                @if($schemes->isEmpty())
                    <p class="text-muted">No schemes found for this subject.</p>
                @else
                    <div class="row g-4"> {{-- Numerical sorting happens in controller or via ->sortBy --}}
                        @foreach ($schemes->sortBy('YearGroup') as $scheme)
                        <h1></h1>
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('schemes.show', $scheme->id) }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm scheme-card">
                                        <div class="card-body p-4">
                                            <h3 class="h5 mb-1 text-dark">Year {{ $scheme->YearGroup }}</h3>
                                            <span class="text-muted small">{{ $selectedSubject->Subject }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    @endif

   @if(request('tab') === 'departmentalClasses')
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Departmental Overview</h2>
            <span class="badge bg-light text-dark border">{{ $departmentalClasses->count() }} Total Classes</span>
        </div>

        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}" class="row g-2">
                    <input type="hidden" name="tab" value="departmentalClasses">
                    
                    <div class="col-md-3">
                        <select name="year_group" class="form-select form-select-sm border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">All Year Groups</option>
                            @foreach(range(8, 14) as $year)
                                <option value="{{ $year }}" {{ request('year_group') == $year ? 'selected' : '' }}>Year {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="teacher_id" class="form-select form-select-sm border-0 shadow-sm" onchange="this.form.submit()">
                            <option value="">All Teachers</option>
                            @foreach($departmentTeachers as $t)
                            <h1></h1>
                                <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->Surname }}, {{ substr($t->FirstName, 0, 1) }}.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('dashboard', ['tab' => 'departmentalClasses']) }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        @php
            // Apply filtering logic to the collection
            $filteredClasses = $departmentalClasses
                ->when(request('year_group'), function($query) {
                    return $query->where('YearGroup', request('year_group'));
                })
                ->when(request('teacher_id'), function($query) {
                    return $query->where('Teacher_id', request('teacher_id'));
                })
                ->sortBy('YearGroup'); // Sort numerically
        @endphp

        @if ($filteredClasses->isEmpty())
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <p class="text-muted mb-0">No classes match those filters.</p>
            </div>
        @else
<div class="row g-5"> 
    @foreach ($filteredClasses as $class)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('class.pupils', $class->id) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm scheme-card">
                    <div class="card-body p-3"> 
                        
                        <h3 class="h5 mb-0 text-dark fw-bold">{{ $class->ClassName }}</h3>
                        
                        <div class="text-muted small mb-2">{{ $class->Subject }}</div>
                        
                        <div class="pt-2 border-top">
                            <div class="small text-dark">
                                <strong>Class Teacher:</strong> {{ optional($class->teacher)->FirstName }} {{ optional($class->teacher)->Surname }}
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
</div>
        @endif
    </div>
@endif
</div>

<style>
    .scheme-card {
        transition: all 0.2s ease-in-out;
        border-left: 4px solid transparent !important;
    }
    .scheme-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
        border-left: 4px solid #0d6efd !important;
    }
</style>
@endsection