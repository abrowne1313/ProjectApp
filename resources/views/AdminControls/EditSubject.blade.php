@extends('layouts.default')

@section('title', 'Edit Subject Details')

@section('content')

<h1>Edit Subject Details</h1>

<form action="{{ route('subject.update', $subject->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="Subject">Subject</label>
    <input type="text"
           id="Subject"
           name="Subject"
           value="{{ old('Subject', $subject->Subject) }}"
           required>

    <label for="HoD_Teacher_id">Head of Department</label>
    <select name="HoD_Teacher_id" id="HoD_Teacher_id" required>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}"
                {{ $teacher->id == $subject->HoD_Teacher_id ? 'selected' : '' }}>
                {{ $teacher->FirstName }} {{ $teacher->Surname }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn-primary">
        Update Subject Information
    </button>
</form>

@endsection
