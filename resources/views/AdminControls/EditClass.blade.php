@extends('layouts.default')

@section('title', 'Edit Class')

@section('content')
<h1>Edit Class</h1>

<form action="{{ route('class.update', $class->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Class Name</label>
    <input type="text" name="ClassName" value="{{ old('ClassName', $class->ClassName) }}" required>

    <label>Subject</label>
    <input type="text" name="Subject" value="{{ old('Subject', $class->Subject) }}" required>

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
