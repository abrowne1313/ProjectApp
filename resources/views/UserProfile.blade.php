@extends('layouts.default')

@section('content')
<div class="container">

    <h2>User Profile</h2>

    <div class="card p-4">

        <p><strong>Name:</strong> {{ $user->FirstName}} {{$user->Surname }}</p>

        <p><strong>Email address:</strong> {{ $user->UserEmail }}</p>

        <p><strong>Password:</strong> ********</p>

         <p>
            <strong>User Type:</strong> 
            <span class="badge bg-secondary">
                {{ $user->type->usertype ?? 'Unassigned' }}
            </span>
        </p>

        <a href="{{ route('ChangePassword') }}" class="btn btn-primary mt-3">
            Change Password
        </a>

    </div>

</div>
@endsection
