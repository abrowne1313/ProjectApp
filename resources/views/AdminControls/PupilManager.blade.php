@extends('layouts.default')

@section('title', 'Pupil Manager')

@section('content')
<h1>Pupil Manager</h1>

<a href="{{ route('CreatePupil') }}">
    <button type="button">Create New Pupil</button>
</a>

<table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
    <thead>
        <tr>
            <th>Name</th>
            <th>Form class</th>
            <th>Classes</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($pupils as $pupil)
            <tr>
                <td>{{ $pupil->FirstName }} {{ $pupil->Surname }}</td>
                <td>{{ $pupil->FormClass }}</td>

                <td>
                    @if ($pupil->classes->count() > 0)
                        <ul>
                            @foreach ($pupil->classes as $class)
                                <li>
                                    {{ $class->Subject }}
                                    (Teacher: {{ $class->teacher->FirstName}} {{$class->teacher->Surname ?? 'N/A' }})
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <em>No classes assigned</em>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
