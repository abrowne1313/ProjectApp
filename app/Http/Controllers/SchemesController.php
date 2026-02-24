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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new scheme.
     */
public function create()
{
    $subject = Subject::where('HoD_Teacher_id', auth()->id())->firstOrFail();
    return view('HoDControls.CreateScheme', compact('subject'));
}


    /**
     * Store a newly created scheme in storage.
     */
public function store(Request $request)
{
$request->validate([
    'YearGroup' => 'required|integer',
    'topics' => 'required|array',
    'topics.*' => 'required|string',
    'max_scores' => 'array',
    'max_scores.*' => 'integer|min:1'
]);


    $subject = Subject::where('HoD_Teacher_id', auth()->id())->firstOrFail();

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
    public function edit(Schemes $schemes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schemes $schemes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schemes $schemes)
    {
        //
    }
}
