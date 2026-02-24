@extends('layouts.default')

@section('title', 'Class Pupils')

@section('content')

<h1>{{ $class->ClassName }}</h1>
<p><strong>Subject:</strong> {{ $class->Subject }}</p>
<p><strong>Teacher:</strong>
    {{ optional($class->teacher)->FirstName }}
    {{ optional($class->teacher)->Surname }}
</p>

<hr>

<h2>Pupils in this class</h2>

@if ($class->pupils->count())

  

    <form method="POST" action="{{ route('class.scores.save', $class->id) }}">
        @csrf
  <h2>Pupil Scores</h2>
  <button type="submit" class="btn btn-primary mt-3">Save scores</button>
        <table class="table table-bordered">
<thead>
    <tr>
        <th class="rotate"><div>Name</div></th>
        <th class="rotate"><div>Form Class</div></th>
        <th class="rotate"><div>Target</div></th> 
        @foreach ($topics as $topic)
            <th class="rotate">
                <div><span>{{ $topic->Title }}</span></div>
            </th>
        @endforeach
    </tr>
</thead>

            <tbody>
                @foreach ($class->pupils as $rowIndex => $pupil)
                    <tr>
                        <td>{{ $pupil->FirstName }} {{ $pupil->Surname }}</td>
                        <td>{{ $pupil->FormClass }}</td>
                    
                        <td>{{ $targets[$pupil->id]->Target ?? '' }}</td>
                                <td >
                                    <input
                                        type="number"
                                        name="targets[{{ $pupil->id }}]"
                                        value="{{ $targets[$pupil->id]->Target ?? '' }}"
                                        class="form-control score-input"
                                        min="0"
                                        max="100"
                                        data-row="{{ $rowIndex }}"
                                        data-col="2"
                                    >
                                </td>

                        @foreach ($topics as $colIndex => $topic)
                            @php
                                $key = $pupil->id . '-' . $topic->id;
                                $score = $scores[$key][0]->Score ?? null;
                                $target = $targets[$pupil->id]->Target ?? null;

                                $colourClass = '';

                                if ($score !== null && $target !== null && $target > 0) {
                                    $percent = ($score / $target) * 100;

                                    if ($percent >= 105) {
                                        $colourClass = 'score-green';
                                    } elseif ($percent >= 96) {
                                        $colourClass = 'score-amber';
                                    } else {
                                        $colourClass = 'score-red';
                                    }
                                }
                            @endphp

                            <td class="{{ $colourClass }}">
                            @php
                                $key = $pupil->id . '-' . $topic->id;
                                $existingScore = $scores[$key][0]->Score ?? '';
                            @endphp
                            <td class="{{ $class }}">
                                <input
                                    type="number"
                                    name="scores[{{ $pupil->id }}][{{ $topic->id }}]"
                                    value="{{ $existingScore }}"
                                    class="form-control score-input"
                                    min="0"
                                    max="100"
                                    data-row="{{ $rowIndex }}"
                                    data-col="{{ $colIndex }}"
                                >
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        
    </form>

@else
    <p><em>No pupils assigned to this class.</em></p>
@endif






<form method="POST" action="{{ route('class.pupil.add', $class->id) }}">
    @csrf
<h2>Add existing pupil</h2>
    <select name="pupil_id" required>
        <option value="">Select pupil</option>
        @foreach ($availablePupils as $pupil)
            <option value="{{ $pupil->id }}">
                {{ $pupil->FirstName }} {{ $pupil->Surname }} {{ $pupil->FormClass }}
            </option>
        @endforeach
    </select>

    <button type="submit">Add</button>
</form>

<!-- //To allow arrow keys to work to navigate on scores table -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".score-input");
    inputs.forEach(input => {
        input.addEventListener("keydown", function (e) {
            const row = parseInt(this.dataset.row);
            const col = parseInt(this.dataset.col);

            let targetRow = row;
            let targetCol = col;

            if (e.key === "ArrowRight") {
                targetCol = col + 1;
            } else if (e.key === "ArrowLeft") {
                targetCol = col - 1;
            } else if (e.key === "ArrowDown") {
                targetRow = row + 1;
            } else if (e.key === "ArrowUp") {
                targetRow = row - 1;
            } else {
                return; // let other keys behave normally
            }

            e.preventDefault();

            const next = document.querySelector(
                `.score-input[data-row="${targetRow}"][data-col="${targetCol}"]`
            );

            if (next) {
                next.focus();
                next.select();
            }
        });
    });
});
</script>

@endsection



