@extends('layouts.default')


@section('title', 'Create New Pupil')

@section('content')  
  <h1>Create a new Pupil Profile</h1>

    



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

    <!-- Pupil creation form -->
    <form action="{{ route('pupildata.store') }}" method="POST">
        @csrf


        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" value="{{ old('FirstName') }}" required>
        <br><br>

        <label for="Surname">Surname:</label>
        <input type="text" id="Surname" name="Surname" value="{{ old('Surname') }}" required>
        <br><br>

        <label for="YearGroup">Year Group:</label>
        <select id="YearGroup" name="YearGroup" value="{{ old('YearGroup') }}" required>
         <option value="">-- Select Year Group --</option>
            <option value="8" {{ old('YearGroup') == '8' ? 'selected' : '' }}>Year 8</option>
            <option value="9" {{ old('YearGroup') == '9' ? 'selected' : '' }}>Year 9</option>
            <option value="10" {{ old('YearGroup') == '10' ? 'selected' : '' }}>Year 10</option>
            <option value="11" {{ old('YearGroup') == '11' ? 'selected' : '' }}>Year 11</option>
            <option value="12" {{ old('YearGroup') == '12' ? 'selected' : '' }}>Year 12</option>
            <option value="13" {{ old('YearGroup') == '13' ? 'selected' : '' }}>Year 13</option>
            <option value="14" {{ old('YearGroup') == '14' ? 'selected' : '' }}>Year 14</option>
        </select>
        <br><br>

        <label for="DateOfBirth">Date of Birth:</label>
        <input type="date" id="DateOfBirth" name="DateOfBirth" value="{{ old('DateOfBirth') }}" required>
        <br><br>

        <label for="Gender">Gender:</label>
        <select id="Gender" name="Gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" {{ old('Gender') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Non-Binary" {{ old('Gender') == 'Non-Binary' ? 'selected' : '' }}>Non-Binary</option>
            <option value="Prefer not to say" {{ old('Gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
        </select>
        <br><br>

        <label for="FormClass">Form Class:</label>
        <input type="text" id="FormClass" name="FormClass" >
        <br><br>

        <label for="SEN">SEN:</label>
        <input type="text" id="SEN" name="SEN" >
        <br><br>

        <label for="Medical">Medical:</label>
        <input type="text" id="Medical" name="Medical" >
        <br><br>

        <button type = "submit">Create New Pupil</button>

     </form>


@endsection

