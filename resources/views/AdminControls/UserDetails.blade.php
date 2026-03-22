@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="h3 mb-0">User Profile: {{ $user->FirstName }} {{ $user->Surname }}</h2>

    </div>
    <div class="card p-4">

        <p><strong>Name:</strong> {{ $user->FirstName}} {{$user->Surname }}</p>

        <p><strong>Email address:</strong> {{ $user->UserEmail }}</p>

        <p><strong>Password:</strong> ******** <a href="{{ route('ChangeUserPassword', $user->id) }}" class="btn btn-sm btn-outline-secondary">Change User Password</a></p> 

        <p>
            <strong>User Type:</strong> 
            <span class="badge bg-secondary">
                {{ $user->type->usertype ?? 'Unassigned' }}
            </span>
        </p>


    </div>
            <div>
            <a href="{{ route('EditUser', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit Details</a>
            
        </div>

        <h1></h1>
    <h2 class="h5 mb-3">Assigned Classes</h2>
    @if ($classes->isEmpty())
        <div class="text-center py-5 bg-white rounded shadow-sm border">
            <p class="text-muted mb-0">No classes assigned to this user.</p>
        </div>
    @else
        <div class="row g-3"> {{-- Tightened gap --}}
            @foreach ($classes as $class)
                <div class="col-md-4">
                    <a href="{{ route('class.pupils', $class->id) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm scheme-card">
                            <div class="card-body p-3"> {{-- Tightened padding --}}
                                <h4 class="h6 mb-1 text-dark fw-bold">{{ $class->ClassName }}</h4>
                                <div class="text-muted small">{{ $class->Subject }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection