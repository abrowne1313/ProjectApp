@extends('layouts.default')

@section('title', 'Edit Class')

@section('content')
<h1>Edit Class</h1>

<form action="{{ route('class.update', $class->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Class Name</label>
    <input type="text" name="ClassName" value="{{ old('ClassName', $class->ClassName) }}" required>

          <label for="YearGroup">Year Group:</label>
        <select id="YearGroup" name="YearGroup" value="{{ old('YearGroup', $class->YearGroup) }}" required>
         <  <option value="8" {{ old('YearGroup') == '8' ? 'selected' : '' }}>Year 8</option>
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
    <option value="{{ old('Subject', $class->Subject) }}"></option>
    @foreach ($subjects as $subject)
        <option value="{{ $subject->Subject }}" {{ old('Subject') == $subject->Subject ? 'selected' : '' }}>
            {{ $subject->Subject }}
        </option>
    @endforeach
</select>
    <label>Class Teacher</label>
    <select name="teacher_id" required>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}"
                {{ $teacher->id == $class->teacher_id ? 'selected' : '' }}>
                {{ $teacher->FirstName }} {{ $teacher->Surname }}
            </option>
        @endforeach
    </select>

    <button type="submit">Update Class</button>
</form>
@endsection
