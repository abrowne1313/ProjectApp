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


public function saveScores(Request $request, $classId)
{
    $class = ClassLists::findOrFail($classId);

    $subject = Subject::where('Subject', $class->Subject)->first();
    $subjectId = $subject->id;

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
->route('class.pupils', $classId)
->with('status', 'Saved!');
}

   /**
     * Display the revision pack for underachieving topics
     */
public function revisionPack(PupilData $pupil, $subjectID)
{
    $year = $pupil->YearGroup;
    $target = \DB::table('pupil_targets')
        ->where('Pupil_id', $pupil->id)
        ->where('Subject_id', $subjectID)
        ->first();

    $scores = PupilScores::where('Pupil_id', $pupil->id)
        ->whereHas('topic.scheme', function ($q) use ($year, $subjectID) {
            $q->where('YearGroup', $year)->where('Subject_id', $subjectID);
        })
        ->with(['topic.scheme.subject', 'topic.revisionlist'])
        ->get();

    $subject = $scores->first()->topic->scheme->subject ?? null;
    $topicData = [];

    foreach ($scores as $score) {
        $topic = $score->topic;
        $actualScore = $score->Score;

        // Only include topics where they scored LESS than or EQUAL to their target
            if ($actualScore !== null && $actualScore <= ($target->Target ?? 0)) {
                $topicData[] = [
                    'topic' => $topic,
                    'score' => $actualScore,
                    'revisionlist' => $topic->revisionlist->content ?? 'No revision list available.',
                    'url' => $topic->revisionlist->url ?? null
                ];
            }
    }

    // Sort the final list by teaching order for the student's benefit
    $topicData = collect($topicData)->sortBy(function($item) {
        return $item['topic']->TeachingOrder;
    });

    $pdf = \PDF::loadView('PDF.RevisionPack', [
        'pupil' => $pupil,
        'subject' => $subject,
        'target' => $target,
        'topicData' => $topicData
    ]);

    return $pdf->download("{$pupil->FirstName}_{$pupil->Surname}_RevisionPack.pdf");
}






   
}
