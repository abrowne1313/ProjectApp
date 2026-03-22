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
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($pupils as $pupil)
            <tr>
                <td><a href="{{ route('pupil.scores.overview', $pupil->id) }}">
                {{ $pupil->FirstName }} {{ $pupil->Surname }}
            </a></td>
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
                <td>
                    <form action="{{ route('pupils.destroy', $pupil->id) }}" method="POST" 
      onsubmit="return confirm('Are you sure you want to delete this pupil? This action cannot be undone.');" 
      style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="bi bi-trash"></i> Delete
    </button>
</form>
</td>
            </tr>
            
        @endforeach
    </tbody>
</table>

@endsection
