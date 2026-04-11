<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Schemes;
use App\Models\UserData;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display all subjects (Subject Manager page)
     */
    public function index()
    {
        $subjects = Subject::with('hodTeacher')
            ->orderBy('Subject')
            ->get();

        return view('admincontrols.SubjectManager', compact('subjects'));
    }

    /**
     * Show form to create a new subject
     */
public function create()
{
    $teachers = UserData::where('user_type', 3)
    ->orderBy('Surname')->get();
    return view('admincontrols.AddSubject', compact('teachers'));
}


    /**
     * Store a new subject
     */
    public function store(Request $request)
    {
        $request->validate([
            'Subject'         => 'required|string|max:255',
            'HoD_Teacher_id'  => 'required|exists:user_data,id',
        ]);

        Subject::create([
            'Subject'        => $request->Subject,
            'HoD_Teacher_id' => $request->HoD_Teacher_id,
        ]);

        return redirect()
            ->route('subject.manager')
            ->with('success', 'New subject created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Subject $subject)
    {
        $teachers = UserData::select('id', 'FirstName', 'Surname')
            ->where('user_type', 3)
            ->orderBy('Surname')
            ->get();

        return view('admincontrols.EditSubject', compact('subject', 'teachers'));
    }

    /**
     * Update subject
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'Subject'         => 'required|string|max:255',
            'HoD_Teacher_id'  => 'required|exists:user_data,id',
        ]);

        $subject->update([
            'Subject'        => $request->Subject,
            'HoD_Teacher_id' => $request->HoD_Teacher_id,
        ]);

        return redirect()
            ->route('subject.manager')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Delete subject
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('subject.manager')
            ->with('success', 'Subject deleted successfully.');
    }

public function SubjectOverview(Request $request) 
{ 
    $user = auth()->user(); 
    // SHows drop down of all subject overviews for admin users or centre admin
    $isAdmin = in_array($user->user_type, [1, 2]); 

    if ($isAdmin) { 
        $subjects = Subject::all();
    } else { 
                $subjects = Subject::where('HoD_Teacher_id', $user->id)->get(); 
    } 

    if ($subjects->isEmpty()) { 
        abort(403, 'No subjects available.'); 
    }
    $activeSubjectId = $request->get('subject_id', session('active_subject_id', $subjects->first()->id)); 

      if (!$isAdmin && !$subjects->contains('id', $activeSubjectId)) { 
        $activeSubjectId = $subjects->first()->id; 
    }

    session(['active_subject_id' => $activeSubjectId]); 

    $activeSubject = $subjects->where('id', $activeSubjectId)->first(); 
    
   
    $schemes = Schemes::where('Subject_id', $activeSubject->id) 
        ->withCount('topics') 
        ->orderBy('YearGroup','asc') 
        ->get(); 
    
    return view('HoDControls.subjectoverview', compact( 
        'subjects', 
        'activeSubject', 
        'isAdmin', 
        'schemes' 
    )); 
}

}
