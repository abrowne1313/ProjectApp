@extends('layouts.default')


@section('title', 'Class Manager')

@section('content')  
<h1>Class Manager </h1>
    
            <a href="{{ route('CreateClass') }}">
    <button type="button">Create New Class </button>
      </a>
<table>
    <thead>
        <tr>
            <th>Class</th>
            <th>Subject</th>
            <th>Class Teacher</th>
             <th>Actions</th> 
        </tr>
    </thead>
   <tbody>
@foreach ($classes as $class)
<tr>

   <td> {{ $class->ClassName }}</td>

  
    <td>{{ $class->Subject }}</td>
    <td>
        {{ optional($class->teacher)->FirstName }}
        {{ optional($class->teacher)->Surname }}
    </td>
    </a>
   <td>
    <div class="action-buttons">

        <a href="{{ route('class.pupils', $class->id) }}" class="btn-edit">
            View Class list
        </a>
        
        <a href="{{ route('class.edit', $class->id) }}" class="btn-edit">
            Edit Class Details
        </a>

        <form action="{{ route('class.destroy', $class->id) }}"
              method="POST">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn-delete"
                    onclick="return confirm('Delete this class?')">
                Delete
            </button>
        </form>
    </div>
</td>

@endforeach
</tbody>
</table>


@endsection
