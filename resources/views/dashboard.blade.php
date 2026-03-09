@extends('layouts.default')

@section('content')
<div class="container">

    <h1>Dashboard</h1>

    <!-- Link-Based Tabs -->
    <div class="nav nav-pills mb-3">
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


    <!-- MY CLASSES TAB -->
    @if(request('tab') === 'classes' || !request('tab'))
        <div>
            <h2>Find the list of classes below</h2>

            @if ($classes->isEmpty())
                <p>You are not assigned to any classes.</p>
            @else
                <ul>
                    @foreach ($classes as $class)
                        <li>
                            <a href="{{ route('class.pupils', $class->id) }}">
                                {{ $class->ClassName }} {{ $class->Subject }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif


    <!-- SCHEMES TAB -->
    @if(request('tab') === 'schemes')
        <div>
            <h2>Select a Subject</h2>

            <form method="GET" action="{{ route('dashboard') }}">
                <input type="hidden" name="tab" value="schemes">

                <select name="subject_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Choose a subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->Subject }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if(isset($schemes) && $selectedSubject)
                <h3 class="mt-3">Schemes for {{ $selectedSubject->Subject }}</h3>

                @if($schemes->isEmpty())
                    <p class="text-muted">No schemes found for this subject.</p>
                @else
                    <ul class="list-group">
                        @foreach ($schemes as $scheme)
                            <li class="list-group-item">
                                <a href="{{ route('schemes.show', $scheme->id) }}">
                                    {{ $scheme->Title }} (Year {{ $scheme->YearGroup }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
    @endif


    <!-- DEPARTMENTAL CLASSES TAB -->
    @if(request('tab') === 'departmentalClasses')
        <div>
            <h2>Departmental Classes</h2>

            @if ($departmentalClasses->isEmpty())
                <p>No classes found for your department.</p>
            @else
                <ul>
                    @foreach ($departmentalClasses as $class)
                        <li>
                            <a href="{{ route('class.pupils', $class->id) }}">
                                {{ $class->ClassName }} ({{ $class->Subject }})
                                {{ optional($class->teacher)->FirstName }}
                                {{ optional($class->teacher)->Surname }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

</div>
@endsection
