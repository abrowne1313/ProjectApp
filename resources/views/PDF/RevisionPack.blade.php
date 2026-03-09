<h1>{{ $pupil->FirstName }} {{ $pupil->Surname }}</h1>
<h2>Revision Pack – {{ $subject->Subject }}</h2>
<h3>Topic Test Target:{{ $target->Target}}%</h3>

<h4>Topics under target;</h4>
@foreach ($topicData as $item)

    @if ($item['score'] <= $target->Target)
        <h4>{{ $item['topic']->Title }}</h4>

        <p>
            <strong>Score:</strong> {{ $item['score'] }}%
        </p>

        <h5>Revision List</h5>
        <div>{!! nl2br(e($item['revisionlist'])) !!}</div>
    @endif

@endforeach

