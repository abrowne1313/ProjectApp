<?php

namespace App\Http\Controllers;

use App\Models\Schemes;
use App\Models\Subject;
use App\Models\SubTopics;
use App\Models\Topics;
use App\Models\PupilScores;
use App\Models\UserData;
use Illuminate\Http\Request;

class SchemesController extends Controller
{


    /**
     * Show the form for creating a new scheme.
     */
public function create(Request $request)
{
       $subjectId = $request->query('subject_id');

    if ($subjectId) {
        $subject = Subject::find($subjectId);
    } else {
        // if subject not found, use HoD relationship with subject to find create scheme
        $subject = Subject::where('HoD_Teacher_id', auth()->id())->first();
    }

    // erro handling if nothing found
    if (!$subject) {
        return redirect()->back()->with('error', 'Unable to determine your department. Please contact an admin.');
    }

    return view('HoDControls.CreateScheme', compact('subject'));
}


    /**
     * Store a newly created scheme in storage.
     */
public function store(Request $request)
{
$request->validate([
    'subject_id' => 'required|exists:subjects,id',
    'YearGroup' => 'required|integer',
    'topics' => 'required|array',
    'topics.*' => 'required|string',
    'max_scores' => 'array',
    'max_scores.*' => 'nullable|integer|min:1'

]);


    $subject = Subject::findOrFail($request->subject_id);
    
    $scheme = Schemes::create([
        'Subject_id' => $subject->id,
        'YearGroup' => $request->YearGroup,
        'CreatedBy' => auth()->id()
    ]);

foreach ($request->topics as $index => $topicTitle) {
    $scheme->topics()->create([
        'Title' => $topicTitle,
        'TeachingOrder' => $index + 1,
        'MaxTestScore' => $request->max_scores[$index],
    ]);
}


    return redirect()->route('schemes.show', $scheme->id) 
    ->with('success', 'Scheme created successfully.');
}


    /**
     * Display the specified resource.
     */
public function show($id) 
{ $scheme = Schemes::with([ 
    'subject', 
    'creator', 
    'topics' => function ($query) { 
        $query->orderBy('TeachingOrder', 'asc');
         } ])->findOrFail($id); 
         return view('HoDControls.SchemeView', compact('scheme')); }




    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    $scheme = Schemes::with(['subject', 'topics' => function ($q) {
        $q->orderBy('TeachingOrder');
    }])->findOrFail($id);

    return view('HoDControls.EditScheme', compact('scheme'));
}


    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $scheme = Schemes::findOrFail($id);

    $existingTopicIds = $scheme->topics->pluck('id')->toArray();
    $submittedTopicIds = array_keys($request->topics ?? []);

    // Delete topics that were removed in the UI
    $idsToDelete = array_diff($existingTopicIds, $submittedTopicIds);
    if (!empty($idsToDelete)) {
        $scheme->topics()->whereIn('id', $idsToDelete)->delete();
    }

    // Update existing topics
    foreach ($request->topics as $topicId => $data) {
        $scheme->topics()->where('id', $topicId)->update([
            'Title'         => $data['Title'],
            'MaxTestScore'  => $data['MaxTestScore'] ?? null,
            'TeachingOrder' => $data['TeachingOrder'],
        ]);
    }

    // Create new topics
    if ($request->has('new_topics')) {
        foreach ($request->new_topics as $data) {
            $scheme->topics()->create([
                'Title'         => $data['Title'],
                'MaxTestScore'  => $data['MaxTestScore'] ?? null,
                'TeachingOrder' => $data['TeachingOrder'],
            ]);
        }
    }

    return redirect()->route('schemes.show', $scheme->id)
                     ->with('success', 'Scheme updated successfully.');
}




    /**
     * Remove the specified resource from storage.
     */
  public function deleteTopic($id)
{
    Topics::findOrFail($id)->delete();

    return back()->with('success', 'Topic deleted.');
}
}
