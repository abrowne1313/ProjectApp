@extends('layouts.default')


@section('title', 'Login')

@section('content')  
 
    <h1>Login</h1>

    <form action="{{ route('login.submit') }}" method="post">
        @csrf
 

  <label for="email">Full E-mail:</label>
<input type = "email" id="email" name = "UserEmail" placeholder= "Type your email!"
required>
<br><br>


<label for="password">password:</label>
<input type = "password" id="password" name = "password" placeholder= "Enter your password"
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


