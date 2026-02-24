<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassLists;
use App\Models\pupildata;
use Illuminate\Support\Facades\Auth;

class PupilDataController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function CreatePupilForm()
    {
        return view('admincontrols/createpupil');
    }


  /**
     * Store a newly created pupil. (POST /Pupildata)
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'FirstName'     => 'required|string|max:255',
            'Surname'     => 'required|string|max:255',
            'YearGroup'     => 'required|string|max:255',
            'DateOfBirth' => 'required|date|date_format:Y-m-d|before:today',
            'Gender'     => 'required|string|max:255',
            'FormClass'     => 'required|string|max:255',
            'SEN'     => 'nullable|string|max:255',
            'Medical' => 'nullable|string|max:255',
        ]);

            // Create pupil data
        PupilData::create([
            'FirstName'     => $request->FirstName,
            'Surname'     => $request->Surname,
            'YearGroup'     => $request->YearGroup,
            'DateOfBirth' => $request->DateOfBirth,
            'Gender'     => $request->Gender,
            'FormClass'     => $request->FormClass,
            'SEN'    => $request->SEN,
            'Medical'    => $request->Medical
        ]);

        return redirect()->route('CreatePupil')->with('success', 'New Pupil created successfully.');
    }

}
