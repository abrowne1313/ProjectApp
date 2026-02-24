@extends('layouts.default')

@section('content')

<div class="container">

    <h1>
        {{ $topic->scheme->subject->Subject }}
        – Year {{ $topic->scheme->YearGroup }}
    </h1>

    <h2>Topic: {{ $topic->Title }}</h2>
    <p><strong>Teaching Order:</strong> {{ $topic->TeachingOrder }}</p>

    <hr>

    <h3>Subtopics</h3>

    @if($topic->subtopics->count() === 0)
        <p>No subtopics added yet.</p>
    @else
        <ul>
            @foreach ($topic->subtopics as $sub)
                <li>{{ $sub->Title }}</li>
            @endforeach
        </ul>
    @endif

    <hr>

    <h3>Add a New Subtopic</h3>

    <form method="POST" action="{{ route('subtopic.store', $topic->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Subtopic Title</label>
            <input type="text" name="Title" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Add Subtopic
        </button>
    </form>

</div>

@endsection
