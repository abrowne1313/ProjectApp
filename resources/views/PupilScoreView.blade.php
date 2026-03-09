@extends('layouts.default')

@section('content')
<div class="container">

    <h1>{{ $pupil->FirstName }} {{ $pupil->Surname }}</h1>
    <h2>{{ $subject->Subject }} Scores: Year {{ $year }}</h2>

    @if($target)
        <h4 class="mt-3">
            Target score:
            <span class="badge bg-info text-dark">
                {{ $target->Target ?? $target->Target }}
            </span>
        </h4>
    @endif
    
<a href="{{ route('pupil.revisionpack', [$pupil->id, $subject->id]) }}" 
   class="btn btn-primary mb-3">
    Download Revision Pack PDF
</a>

    <table class="table table-striped table-bordered align-middle mt-4">
        <thead class="table-dark">
            <tr>
                <th style="width: 80%">Topic</th>
                <th style="width: 20%">Score</th>
            </tr>
        </thead>

<tbody>
@foreach ($topics as $topic)

    @php
        $scoreValue = $scores[$topic->id]->Score ?? null;
        $targetValue = $target->Target ?? null;   // FIXED

        $class = '';

        if ($scoreValue !== null && $targetValue !== null) {
            $difference = $scoreValue - $targetValue;
            $percentDiff = ($difference / $targetValue) * 100;

            if ($percentDiff >= 10) {
                $class = 'table-success';
            } elseif ($percentDiff >= -10) {
                $class = 'table-warning';
            } else {
                $class = 'table-danger';
            }
        }
    @endphp

    <tr>
        <td>{{ $topic->Title }}</td>
        <td class="text-center fw-bold {{ $class }}">
            {{ $scoreValue ?? '-' }}
        </td>
    </tr>

@endforeach
</tbody>


        <tfoot class="table-light">
            <tr>
                <th>Average Score</th>
                <th class="text-center">
                    {{ round($scores->avg('Score'), 1) }}
                </th>
            </tr>
        </tfoot>
    </table>

</div>
@endsection
