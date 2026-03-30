@extends('layouts.default')


@section('title', 'Create New User')

@section('content')  
     <h1>Create New User</h1>

    



    <!-- Display validation errors -->
    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- User creation form -->
    <form action="{{ route('CreateUser.store') }}" method="POST">
        @csrf


        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" value="{{ old('FirstName') }}" required>
        <br><br>

        <label for="Surname">Surname:</label>
        <input type="text" id="Surname" name="Surname" value="{{ old('Surname') }}" required>
        <br><br>

        <label for="user_type">User Type:</label>
        <select  id="user_type" name="user_type" required>
            <option value="">-- Select User Type --</option>
            <option value="4" {{ old('user_type') == 'Application Administrator' ? 'selected' : '' }}>Teacher User </option>
            <option value="3" {{ old('user_type') == 'Centre Administrator' ? 'selected' : '' }}>Head of Department </option>
            <option value="2" {{ old('user_type') == 'Head of Departmen' ? 'selected' : '' }}>Centre Administrator</option>
            <option value="1" {{ old('user_type') == 'Teacher User' ? 'selected' : '' }}>Application Administrator</option>
        </select>
        <br><br>



        <label for="UserEmail">Email:</label>
        <input type="email" id="UserEmail" name="UserEmail" value="{{ old('UserEmail') }}" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type = "submit">Create New User</button>

     </form>

@endsection

