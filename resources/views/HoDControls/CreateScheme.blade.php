@extends('layouts.default')


@section('title', 'Create Scheme of work')

@section('content')  
 <h1>Create Scheme for {{ $subject->Subject }}</h1>

<form method="POST" action="{{ route('schemes.store') }}">
    @csrf
    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
    <label>Year Group</label>
    <select name="YearGroup" required>
        <option value="">Select</option>
        @foreach([8,9,10,11,12,13,14] as $year)
            <option value="{{ $year }}">{{ $year }}</option>
        @endforeach
    </select>

<h3>Topics</h3>

<div id="topics">
<div class="topic-row">
<input type="text" name="topics[]" placeholder="Topic name" required>
<input type="number" name="max_scores[]" placeholder="Max score" min="1">
    <button type="button"
    class="btn btn-outline-danger btn-sm delete-topic ms-3"
    style="white-space: nowrap;">
        <i class="bi bi-trash"></i>
    </button>
    </div>
</div>

<button type="button" onclick="addTopic()">Add Topic</button>


    <button type="submit">Create Scheme</button>
</form>

<script>
function addTopic() {
    document.getElementById('topics').insertAdjacentHTML(
    'beforeend',
    `<div class="topic-row">
     <input type="text" name="topics[]" placeholder="Topic name" required>
    <input type="number" name="max_scores[]" placeholder="Max score" min="1">
    <button type="button"
    class="btn btn-outline-danger btn-sm delete-topic ms-3"
    style="white-space: nowrap;">
    <i class="bi bi-trash"></i>
    </button>
        </div>`
    );
}
</script>

   






@endsection
