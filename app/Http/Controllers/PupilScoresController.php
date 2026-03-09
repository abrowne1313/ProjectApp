<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PupilScores;
use App\Models\pupildata;
use App\Models\PupilTarget;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\ClassLists;
use App\Models\Subject;
use PDF;




class PupilScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
// public function showClassScores($classId)
// { 
//     dd('SHOW CLASS SCORES IS RUNNING', $classId);
//     $class = ClassLists::with(['pupils', 'scheme.topics'])->findOrFail($classId);

//     // Load scores
//     $scores = PupilScores::whereIn('Pupil_id', $class->pupils->pluck('id'))
//         ->get()
//         ->groupBy( fn($score) => $score->Pupil_id . '-' . $score->Topic_id);

//     // Load targets
//     $subjectId = Subject::where('Subject', $class->Subject)->value('id');
//     $yearGroup = $class->YearGroup;

//     $targets = PupilTarget::whereIn('Pupil_id', $class->pupils->pluck('id'))
//         ->where('Subject_id', $subjectId)
//         ->where('YearGroup', $yearGroup)
//         ->get()
//         ->keyBy('Pupil_id');
 
//     return view('ClassPupilList', [
//         'class' => $class,
//         'topics' => $class->scheme->topics ?? collect(),
//         'scores' => $scores,
//         'targets' => $targets, 
          
//     ]);
   

// }


public function overview(pupildata $pupil)
{
    $scores = PupilScores::where('Pupil_id', $pupil->id)
        ->with('topic.scheme.subject')
        ->get();

    // Group by Year → Subject
    $grouped = $scores->groupBy(function ($score) {
        return $score->topic->scheme->YearGroup;
    })->map(function ($yearGroup) {
        return $yearGroup->groupBy(function ($score) {
            return $score->topic->scheme->subject->id;
        });
    });

    return view('PupilOverview', compact('pupil', 'grouped'));
}


    
public function showYearSubject(pupildata $pupil, $year, $subjectID)
{
    $scores = PupilScores::where('Pupil_id', $pupil->id)
        ->whereHas('topic.scheme', function ($q) use ($year, $subjectID) {
            $q->where('YearGroup', $year)
              ->where('Subject_id', $subjectID);
        })
        ->with('topic.scheme.subject')
        ->get();

    // Extract topics in teaching order
    $topics = $scores->pluck('topic')->unique('id')->sortBy('TeachingOrder');
    $target = \DB::table('pupil_targets')
    ->where('Pupil_id', $pupil->id)
    ->where('Subject_id', $subjectID)
    ->first();

    return view('PupilScoreView', [
        'pupil' => $pupil,
        'year' => $year,
        'subject' => $scores->first()->topic->scheme->subject ?? null,
        'topics' => $topics,
        'scores' => $scores->keyBy('Topic_id'),
        'target' => $target,
    ]);



}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 
    /**
     * Store a newly created resource in storage.
     */


public function saveScores(Request $request, $classId)
{
    $class = ClassLists::findOrFail($classId);

    $subjectId = $class->subjectModel->id;
    $yearGroup = $class->YearGroup;

    /* 
    
       SAVE TARGETS
    */
    $targets = $request->input('targets', []);

    foreach ($targets as $pupilId => $targetValue) {

        if ($targetValue === null || $targetValue === '') {
            continue;
        }

        PupilTarget::updateOrCreate(
            [
                'Pupil_id' => $pupilId,
                'Subject_id' => $subjectId,
                'YearGroup' => $yearGroup,
            ],
            [
                'Target' => $targetValue,
            ]
        );
    }

    /* 
       SAVE TOPIC SCORES
    */
    $scores = $request->input('scores', []);

    foreach ($scores as $pupilId => $topics) {
        foreach ($topics as $topicId => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            PupilScores::updateOrCreate(
                [
                    'Pupil_id' => $pupilId,
                    'Topic_id' => $topicId,
                ],
                [
                    'Score' => $value,
                ]
            );
        }
    }

    return redirect()
        ->back()
        ->with('status', 'Scores and targets saved successfully.');
}






    /**
     * Display the revision pack for underachieving topics
     */
   /**
     * Display the revision pack for underachieving topics
     */
public function revisionPack(PupilData $pupil, $subjectID)
{
    $year = $pupil->YearGroup;

    // 1. Get all scores for this pupil for this subject + year
    $scores = PupilScores::where('Pupil_id', $pupil->id)
        ->whereHas('topic.scheme', function ($q) use ($year, $subjectID) {
            $q->where('YearGroup', $year)
              ->where('Subject_id', $subjectID);
        })
        ->with(['topic.scheme.subject', 'topic.revisionlist'])
        ->get()
        ->keyBy('Topic_id');

    $target = \DB::table('pupil_targets')
    ->where('Pupil_id', $pupil->id)
    ->where('Subject_id', $subjectID)
    ->first();
 
    // 2. Extract topics in teaching order
    $topics = $scores->pluck('topic')
        ->filter() // remove nulls
        ->unique('id')
        ->sortBy('TeachingOrder');

    // 3. Get the subject model
    $subject = $scores->first()->topic->scheme->subject ?? null;

    // 4. Build a simple list of topics with scores + revision lists
    $topicData = [];

    foreach ($topics as $topic) {
        $topicData[] = [
            'topic' => $topic,
            'score' => $scores[$topic->id]->Score ?? null,
            'revisionlist' => $topic->revisionlist->content ?? 'No revision list available.'
        ];
    }

    // 5. Generate PDF
    $pdf = \PDF::loadView('PDF.RevisionPack', [
        'pupil' => $pupil,
        'subject' => $subject,
        'target' => $target,
        'topicData' => $topicData
    ]);

    return $pdf->download("{$pupil->FirstName}_{$pupil->Surname}_RevisionPack.pdf");
}






    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PupilScores $pupilScores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PupilScores $pupilScores)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PupilScores $pupilScores)
    {
        //
    }
}
