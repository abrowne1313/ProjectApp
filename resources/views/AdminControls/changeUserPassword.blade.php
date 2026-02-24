@extends('layouts.default')


@section('title', 'Change User Password')

@section('content')  
 <h1> Enter user details to change password</h1>
    
    <form action="{{ route('ChangeUserPassword.submit') }}" method="post">
        @csrf
 

  <label for="email">User E-mail:</label>
<input type = "email" id="email" name = "UserEmail" placeholder= "Type user email!"
required>
<br><br>


<label for="password">New password:</label>
<input type = "password" id="newpassword1" name = "newpassword1" placeholder= "Enter new password"
required>
<br><br>
<label for="password">Re-enter new password:</label>
<input type = "password" id="newpassword2" name = "newpassword2" placeholder= "Re-enter new password"
required>
<br><br>

<button type="submit"> Submit</button>
    </form>
    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>   
        @endforeach
    </ul>

    @endif

@endsection