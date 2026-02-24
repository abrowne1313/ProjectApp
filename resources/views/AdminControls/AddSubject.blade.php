@extends('layouts.default')

@section('title', 'Add Subject')

@section('content')  
<h1>Add New Subject</h1>

    

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

    <!-- Subject creation form -->
    <form action="{{ route('subject.store') }}" method="POST">
        @csrf

        <label for="Subject">Subject:</label>
        <input type="text" id="Subject" name="Subject" value="{{ old('Subject') }}" required>
        <br><br>

<label for="HoD_Teacher_id">Head of Department:</label>

<select id="HoD_Teacher_id" name="HoD_Teacher_id" required>
    <option value="">-- Select a teacher --</option>

    @foreach ($teachers as $teacher)
        <option value="{{ $teacher->id }}"
            {{ old('HoD_Teacher_id') == $teacher->id ? 'selected' : '' }}>
            {{ $teacher->FirstName }} {{ $teacher->Surname }}
        </option>
    @endforeach
</select>
<br><br>


        <button type = "submit">Create New Subject</button>

     </form>


@endsection