@extends('layouts.default')

@section('content')
<div class="container">
    <h1>{{ $pupil->FirstName }} {{ $pupil->Surname }} – Score History</h1>

    @foreach ($grouped as $year => $subjects)
        <h2>Year {{ $year }}</h2>
        <ul>
    @foreach ($subjects as $subjectId => $scores)
    @php
    $subject = $scores->first()->topic->scheme->subject;
       @endphp

   <li>
        <a href="{{ route('pupil.scores.show', [
            'pupil' => $pupil->id,
             'year' => $year,
             'subject' => $subjectId]) }}">
       {{ $subject->Subject }}
       </a>
    </li>
            @endforeach
 </ul>
    @endforeach
</div>
@endsection
