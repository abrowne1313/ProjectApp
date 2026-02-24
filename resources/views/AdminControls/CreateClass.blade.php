@extends('layouts.default')


@section('title', 'Create Class')

@section('content')  
<h1>Create New Class </h1>
    



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
    <form action="{{ route('CreateClass') }}" method="POST">
        @csrf


        <label for="ClassName">Class Name:</label>
        <input type="text" id="ClassName" name="ClassName" value="{{ old('ClassName') }}" required>
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

<label for="Subject">Subject</label>
<select name="Subject" id="Subject" required>
    <option value="">-- Select a subject --</option>
    @foreach ($subjects as $subject)
        <option value="{{ $subject->Subject }}" {{ old('Subject') == $subject->Subject ? 'selected' : '' }}>
            {{ $subject->Subject }}
        </option>
    @endforeach
</select>


<label for="teacher_id">Class teacher:</label>
<select id="teacher_id" name="teacher_id" required>
    <option value="">-- Select a teacher --</option>

    @foreach ($teachers as $teacher)
        <option value="{{ $teacher->id }}"
            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
            {{ $teacher->FirstName }} {{ $teacher->Surname }}
        </option>
    @endforeach
</select>
<br><br>


        <button type = "submit">Create New Class</button>

     </form>


@endsection