@extends('layouts.default')


@section('title', 'Edit Pupil Data')

@section('content')  
<h1>Edit Pupil Data </h1>

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
    
<form action="{{ route('pupil.update', $pupil->id) }}" method="POST">
    @csrf
    @method('PUT')
    
     <label for="FirstName">First Name:</label>
    <input type="text" name="FirstName" value="{{ old('FirstName', $pupil->FirstName) }}">
        
        <label for="Surname">Surname:</label>
        <input type="text" id="Surname" name="Surname" value="{{ old('Surname', $pupil->Surname) }}">
              
        <label for="YearGroup">Year Group:</label>
        <select id="YearGroup" name="YearGroup" value="{{ old('YearGroup', $pupil->YearGroup) }}" >
            <option value="8" {{ old('YearGroup', $pupil->YearGroup) == '8' ? 'selected' : '' }}>Year 8</option>
            <option value="9" {{ old('YearGroup', $pupil->YearGroup) == '9' ? 'selected' : '' }}>Year 9</option>
            <option value="10" {{ old('YearGroup', $pupil->YearGroup) == '10' ? 'selected' : '' }}>Year 10</option>
            <option value="11" {{ old('YearGroup', $pupil->YearGroup) == '11' ? 'selected' : '' }}>Year 11</option>
            <option value="12" {{ old('YearGroup', $pupil->YearGroup) == '12' ? 'selected' : '' }}>Year 12</option>
            <option value="13" {{ old('YearGroup', $pupil->YearGroup) == '13' ? 'selected' : '' }}>Year 13</option>
            <option value="14" {{ old('YearGroup', $pupil->YearGroup) == '14' ? 'selected' : '' }}>Year 14</option>
        </select>
       
        <label for="DateOfBirth">Date of Birth:</label>
        <input type="date" id="DateOfBirth" name="DateOfBirth" value="{{ old('DateOfBirth', $pupil->DateOfBirth) }}" >
       
        <label for="Gender">Gender:</label>
        <select id="Gender" name="Gender" value="{{ old('Gender', $pupil->Gender) }}">
            <option value="">-- Select Gender --</option>
            <option value="Male" {{ old('Gender', $pupil->Gender) == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('Gender', $pupil->Gender) == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Non-Binary" {{ old('Gender', $pupil->Gender) == 'Non-Binary' ? 'selected' : '' }}>Non-Binary</option>
            <option value="Prefer not to say" {{ old('Gender', $pupil->Gender) == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
        </select>
       
        <label for="FormClass">Form Class:</label>
        <input type="text" id="FormClass" name="FormClass" value="{{ old('FormClass', $pupil->FormClass) }}" >
       
        <label for="SEN">SEN:</label>
        <input type="text" id="SEN" name="SEN" value="{{ old('SEN', $pupil->SEN) }}">
        
        <label for="Medical">Medical:</label>
        <input type="text" id="Medical" name="Medical" value="{{ old('Medical', $pupil->Medical) }}">
        
    
    
    <button type="submit">Update Pupil</button>
</form>


@endsection

