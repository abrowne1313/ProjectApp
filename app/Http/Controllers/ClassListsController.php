<?php

namespace App\Http\Controllers;

use App\Models\ClassLists;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use App\Models\UserData;
use App\Models\Subject;
use App\Models\Schemes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClassListsController extends Controller
{
    /**
     * Display form to create a new class (/createclass).
     */
public function CreateClassForm()
{
    $teachers = UserData::select('id', 'FirstName', 'Surname')
        ->orderBy('Surname')
        ->get();

    $subjects = Subject::select('Subject')
        ->orderBy('Subject')
        ->get();

    return view('admincontrols.CreateClass', compact('teachers', 'subjects'));
}

    /**
     * Store a newly created class in the class_lists database.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'ClassName'     => 'required|string|max:255',
            'YearGroup'     => 'required|string|max:255',
            'Subject' => 'required|string|exists:subjects,Subject',
            'teacher_id' => 'required|exists:user_data,id'
         ]);

                 // Create a new class
        ClassLists::create([
            'ClassName'     => $request->ClassName,
            'YearGroup'     => $request->YearGroup,
            'Subject' => $request->Subject,
            'teacher_id' => $request->teacher_id
        ]);

        return redirect()->route('CreateClass')->with('success', 'New class created successfully.');
        }

        // function to show class lists with teacher in class manager
public function index()
{
    $classes = ClassLists::with('teacher')->get();

    return view('admincontrols.classmanager', [
            'classes' => $classes
        ]);
}

/**
 * Show edit form
 */
public function edit(ClassLists $class)
{
    $teachers = UserData::select('id', 'FirstName', 'Surname')
        ->orderBy('Surname')
        ->get();

    return view('admincontrols.EditClass', [
        'class' => $class,
        'teachers' => $teachers
    ]);
}

/**
 * Update class
 */
public function update(Request $request, ClassLists $class)
{
    $request->validate([
        'ClassName'  => 'required|string|max:255',
        'YearGroup'     => 'required|string|max:255',
        'Subject'    => 'required|string|max:255',
        'teacher_id' => 'required|exists:user_data,id',
    ]);

    $class->update($request->only([
        'ClassName',
        'YearGroup',
        'Subject',
        'teacher_id',
    ]));

    return redirect()->route('class.manager')
        ->with('success', 'Class updated successfully.');
}

/**
 * Delete class
 */
public function destroy(ClassLists $class)
{
    $class->delete();

    return redirect()->route('class.manager')
        ->with('success', 'Class deleted successfully.');
}


// /Cosnider this method if we want to disable add/delete controls of pupils for standard users
// public function show(ClassLists $class)
// {
//     $pupils = $class->pupils;

//     return view('class.pupils', compact('class', 'pupils'));
// }

// public function pupils(ClassLists $class)
// {
//     $class->load('pupils', 'teacher');



//     return view('ClassPupilList', compact(
//         'class',
//         'availablePupils'
//     ));
// }

// public function pupils($classId)
// {
//     $class = ClassLists::with('pupils')->findOrFail($classId);

//     // Convert subject NAME to subject ID
//     $subject = Subject::where('Subject', $class->Subject)->first();

//     if (!$subject) {
//         // No matching subject found
//         $topics = collect();
//         $scores = collect();
//         return view('ClassPupilList', compact('class', 'topics', 'scores'));
//     }

//     $subjectId = $subject->id;
//     $yearGroup = $class->YearGroup;

//     // Load the scheme for this subject & year
//     $scheme = Schemes::where('Subject_id', $subjectId)
//                      ->where('YearGroup', $yearGroup)
//                      ->with('topics')
//                      ->first();

//     $topics = $scheme ? $scheme->topics : collect();

//     // Load all existing scores for pupils in this class
//     $scores = PupilScores::whereIn('Pupil_id', $class->pupils->pluck('id'))
//                          ->get()
//                          ->groupBy(function ($score) {
//                              return $score->Pupil_id . '-' . $score->Topic_id;
//                          });

//     // Pupils NOT already in this class
//     $availablePupils = PupilData::whereDoesntHave('classes', function ($q) use ($class) {
//         $q->where('class_lists.id', $class->id);
//     })
//     ->orderBy('Surname')
//     ->get();

//     return view('ClassPupilList', compact('class', 'topics', 'scores', 'availablePupils'));
// }
public function pupils($classId)
{
    // Load class + pupils
    $class = ClassLists::with('pupils')->findOrFail($classId);

    // Convert subject NAME to subject ID
    $subject = Subject::where('Subject', $class->Subject)->first();

    if (!$subject) {
        return view('ClassPupilList', [
            'class' => $class,
            'topics' => collect(),
            'scores' => collect(),
            'targets' => collect(),
            'availablePupils' => collect(),
        ]);
    }

    $subjectId = $subject->id;
    $yearGroup = $class->YearGroup;

    // Load the scheme for this subject & year
    $scheme = Schemes::where('Subject_id', $subjectId)
                     ->where('YearGroup', $yearGroup)
                     ->with('topics')
                     ->first();

    $topics = $scheme ? $scheme->topics : collect();

    // Load scores
    $scores = PupilScores::whereIn('Pupil_id', $class->pupils->pluck('id'))
        ->get()
        ->groupBy(fn($score) => $score->Pupil_id . '-' . $score->Topic_id);

    // LOAD TARGETS 
    $targets = PupilTarget::whereIn('Pupil_id', $class->pupils->pluck('id'))
        ->where('Subject_id', $subjectId)
        ->where('YearGroup', $yearGroup)
        ->get()
        ->keyBy('Pupil_id');

    // Pupils NOT in this class
    $availablePupils = PupilData::whereDoesntHave('classes', function ($q) use ($class) {
        $q->where('class_lists.id', $class->id);
    })
    ->orderBy('Surname')
    ->get();

    return view('ClassPupilList', [
        'class' => $class,
        'topics' => $topics,
        'scores' => $scores,
        'targets' => $targets,   
        'availablePupils' => $availablePupils,
    ]);
}





public function addPupil(Request $request, ClassLists $class)
{
    $request->validate([
        'pupil_id' => 'required|exists:pupil_data,id',
    ]);

    $class->pupils()->attach($request->pupil_id);

    return back()->with('success', 'Pupil added to class.');
}


public function removePupil(ClassLists $class, PupilData $pupil)
{
    $class->pupils()->detach($pupil->id);

    return back()->with('success', 'Pupil removed from class.');
}


}


   


