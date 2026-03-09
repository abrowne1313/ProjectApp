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