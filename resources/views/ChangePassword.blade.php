@extends('layouts.default')


@section('title', 'Change Password')

@section('content')  
 <h1> Enter user details to change password</h1>
    
<form action="{{ route('ChangePassword.submit') }}" method="POST">
    @csrf

    <label>Old Password</label>
    <input type="password" name="oldpassword" required>

    <label>New Password</label>
    <input type="password" name="newpassword1" required>

    <label>Confirm New Password</label>
    <input type="password" name="newpassword2" required>

    <button type="submit">Update Password</button>
</form>

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>   
        @endforeach
    </ul>

    @endif

@endsection