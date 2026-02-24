@extends('layouts.default')

@section('title', 'Subject Manager')

@section('content')

<h1>Subject Manager</h1>

<a href="{{ route('subject.create') }}" class="btn-primary">
    Add New Subject
</a>

@if ($subjects->count())

<table>
    <thead>
        <tr>
            <th scope="col">Subject</th>
            <th scope="col">Head of Department</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->Subject }}</td>

                <td>
                    {{ optional($subject->hodTeacher)->FirstName }}
                    {{ optional($subject->hodTeacher)->Surname }}
                </td>

                <td>
                    <div class="action-buttons">
                        <a href="{{ route('subject.edit', $subject->id) }}"
                           class="btn-edit">
                            Edit
                        </a>

                        <form action="{{ route('subject.destroy', $subject->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn-delete"
                                    onclick="return confirm('Delete this subject?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@else
    <p><em>No subjects have been created yet.</em></p>
@endif

@endsection
