@extends('layouts.default')

@section('content')

<div class="container">

   <h1>{{ $scheme->subject->Subject }} – Year {{ $scheme->YearGroup }} Scheme</h1>

   <p>
    <strong>Created by:</strong>
    {{ $scheme->creator->FirstName }} {{ $scheme->creator->Surname }}
</p>
            @if(in_array(auth()->user()->user_type, [1, 2]))
                 <a href="{{ route('scheme.edit', $scheme->id) }}"
                    class="btn-edit">
                    Edit
                    </a>
        @endif

    <h2>Topics</h2>

    @if($scheme->topics->count() === 0)
        <p>No topics added yet.</p>
    @else
        <ol>
            @foreach ($scheme->topics as $topic)
                <li>
                    <a
                href="{{ route('topic.show', $topic->id) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
            >
                <span>
                    <strong>{{ $topic->Title }}</strong>
                </span>

                <span class="badge bg-primary rounded-pill">
                    
                </span>
            </a>
                </li>
            @endforeach
        </ol>
    @endif

</div>

@endsection
