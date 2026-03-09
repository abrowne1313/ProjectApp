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

    <h3>Revision List</h3>

    @if(!$topic->revisionlist)
        <p>No revision list added yet.</p>
    @else
        <div class="border p-3">
            {!! nl2br(e($topic->revisionlist->content)) !!}
        </div>
    @endif

    <hr>

    <h3>Edit Revision List</h3>

    <form method="POST" action="{{ route('revisionlists.save', $topic->id) }}">
        @csrf

        <textarea name="content" class="form-control" rows="10" required>
            {{ $topic->revisionlist->content ?? '' }}
        </textarea>

        <button type="submit" class="btn btn-primary mt-3">
            Save Revision List
        </button>
    </form>

</div>

@endsection
