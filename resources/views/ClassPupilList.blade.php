@extends('layouts.default')

@section('title', 'Class Pupils')

@section('content')
<style>
    /* CSS to narrow the forms to produce a cleaner look */
    .score-input {
        width: 65px !important; /* Fixed width for scores */
        padding: 4px 2px;
        text-align: center;
        margin: 0 auto;
    }

    /* Adjust table cell padding to keep it tight */
    .table td, .table th {
        padding: 0.5rem 0.25rem !important;
        vertical-align: middle;
    }

    /* Styling for colored background cells */
    .score-green { background-color: #d4edda !important; }
    .score-amber { background-color: #fff3cd !important; }
    .score-red   { background-color: #f8d7da !important; }

    .table td:first-child {
        min-width: 150px;
        white-space: nowrap;
    }
</style>
<h1>{{ $class->ClassName }}</h1>
<p><strong>Subject:</strong> {{ $class->Subject }}</p>
<p><strong>Teacher:</strong>
    {{ optional($class->teacher)->FirstName }}
    {{ optional($class->teacher)->Surname }}
</p>

<hr>

<h2>Pupils in this class</h2>

@if ($class->pupils->count())
    {{-- MAIN FORM FOR SAVING SCORES --}}
    <form method="POST" action="{{ route('class.scores.save', $class->id) }}">
        @csrf
        <h2>Pupil Scores</h2>
        <button type="submit" class="btn btn-primary mt-3 mb-3">Save scores</button>
        
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
        <th class ="rotate"><div>Remove Pupil</div></th>
                </tr>
            </thead>

<tbody>
     @foreach ($class->pupils as $rowIndex => $pupil)
         <tr>
            <td>
             <a href="{{ route('pupil.scores.overview', $pupil->id) }}">
                  {{ $pupil->FirstName }} {{ $pupil->Surname }}
             </a>
            </td>

            <td>{{ $pupil->FormClass }}</td>

             <td>
             <input
                name="targets[{{ $pupil->id }}]"
                value="{{ $targets[$pupil->id]->Target ?? '' }}"
                 class="form-control score-input"
                min="0"
                max="100"
                data-row="{{ $rowIndex }}"
                data-col="target"
             >
                        </td>

    @foreach ($topics as $colIndex => $topic)
        @php
            $key = $pupil->id . '-' . $topic->id;
             $existingScore = $scores[$key][0]->Score ?? null;
            $targetValue = $targets[$pupil->id]->Target ?? null;

            $existingScore = is_numeric($existingScore) ? (float) $existingScore : null;
            $targetValue = is_numeric($targetValue) ? (float) $targetValue : null;

             $colourClass = '';
            if ($existingScore !== null && $targetValue !== null && $targetValue > 0) {
            $percent = ($existingScore / $targetValue) * 100;
            if ($percent >= 105) { $colourClass = 'score-green';
            } elseif ($percent >= 96) { $colourClass = 'score-amber';
                  } else {$colourClass = 'score-red'; }
            }
        @endphp 

<td class="{{ $colourClass }}">
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

<td class="text-center">
    {{-- Delete button at the end of each row to remove pupil from class --}}
      <button type="button" 
    class="btn btn-sm btn-outline-danger"
        onclick="if(confirm('Remove {{ $pupil->FirstName }} from this class?')) { document.getElementById('delete-form-{{ $pupil->id }}').submit(); }">
    <i class="bi bi-x-circle"></i> Remove
          </button>
 </td>
 </tr>
     @endforeach
        </tbody>
        </table>
    </form>
@else
    <p><em>No pupils assigned to this class.</em></p>
@endif

<hr>

{{-- FORM FOR ADDING PUPILS --}}
<form method="POST" action="{{ route('class.pupil.add', $class->id) }}">
    @csrf
    <h2>Add existing pupil</h2>
    <div class="input-group mb-3" style="max-width: 400px;">
    <select name="pupil_id" class="form-select" required>
     <option value="">Select pupil</option>
      @foreach ($availablePupils as $pupil)
      <option value="{{ $pupil->id }}">
     {{ $pupil->FirstName }} {{ $pupil->Surname }} ({{ $pupil->FormClass }})
     </option>
            @endforeach
        </select>
        <button class="btn btn-success" type="submit">Add</button>
    </div>
</form>

{{-- HIDDEN DELETE FORMS (One for each pupil) --}}
@if ($class->pupils->count())
    @foreach ($class->pupils as $pupil)
        <form id="delete-form-{{ $pupil->id }}" 
      action="{{ route('class.pupil.remove', [$class->id, $pupil->id]) }}" 
      method="POST" 
      tyle="display: none;">
    @csrf
    @method('DELETE')
        </form>
    @endforeach
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".score-input");
    inputs.forEach(input => {
       input.addEventListener("keydown", function (e) {
        const row = parseInt(this.dataset.row);
       const col = isNaN(this.dataset.col) ? -1 : parseInt(this.dataset.col);

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
      } else {return;

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