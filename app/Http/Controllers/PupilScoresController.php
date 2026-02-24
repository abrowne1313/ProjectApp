<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use App\Models\ClassLists;

class PupilScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function showClassScores($classId)
{
    $class = ClassLists::with(['pupils', 'scheme.topics'])->findOrFail($classId);

    // Fetch all scores for pupils in this class
    $scores = PupilScores::whereIn('Pupil_id', $class->pupils->pluck('id'))
        ->get()
        ->groupBy(function ($score) {
            return $score->Pupil_id . '-' . $score->Topic_id;
        });

    return view('ClassPupilList', [
        'class' => $class,
        'topics' => $class->scheme->topics,
        'scores' => $scores,
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

    $subjectId = $class->scheme->Subject_id;
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
     * Display the specified resource.
     */
    public function show(PupilScores $pupilScores)
    {
        //
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
