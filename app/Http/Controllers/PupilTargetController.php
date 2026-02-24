<?php

namespace App\Http\Controllers;

use App\Models\PupilTarget;
use App\Models\ClassLists;
use Illuminate\Http\Request;

class PupilTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function showPupilTargets($classId)
{
    $class = ClassLists::with(['pupils', 'scheme.topics'])->findOrFail($classId);

    $yearGroup = $class->YearGroup;
    $subjectId = $class->scheme->Subject_id;

    // Load targets for this class's pupils, subject, and year group
    $targets = PupilTarget::whereIn('Pupil_id', $class->pupils->pluck('id'))
        ->where('Subject_id', $subjectId)
        ->where('YearGroup', $yearGroup)
        ->get()
        ->keyBy('Pupil_id');

    return view('ClassPupilList', [
        'class' => $class,
        'topics' => $class->scheme->topics,
        'Target' => $targets,
        'subject_id' => $subjectId,   
        'yearGroup' => $yearGroup,   
    ]);
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //Not required-Method for saving targets included in saveScores method in PupilScoresCont
// publ


    /**
     * Display the specified resource.
     */
    public function show(PupilTarget $pupilTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PupilTarget $pupilTarget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PupilTarget $pupilTarget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PupilTarget $pupilTarget)
    {
        //
    }
}
