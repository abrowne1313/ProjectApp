<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use App\Models\PupilData;

class SearchController extends Controller
{
    // public function search(Request $request)
    // {
    //     $request->validate([
    //         'q' => 'required|string',
    //         'type' => 'required|string'
    //     ]);

    //     $query = $request->q;
    //     $type  = $request->type;
    //     $user  = auth()->user();

    //     // Standard users: pupils only
    //     if ($user->user_type > 2 && $type !== 'pupils') {
    //         abort(403);
    //     }

    //     if ($type === 'users') {
    //         $results = UserData::where('FirstName', 'like', "%$query%")
    //             ->orWhere('Surname', 'like', "%$query%")
    //             ->get();
    //     } else {
    //         $results = PupilData::where('FirstName', 'like', "%$query%")
    //             ->orWhere('Surname', 'like', "%$query%")
    //             ->get();
    //     }

    //     return view('search.results', compact('results', 'type', 'query'));
    // }



public function live(Request $request)
{
    $query = trim($request->q);
    $user  = auth()->user();

    if (!$query || strlen($query) < 2) {
        return response()->json([]);
    }

    $results = [];

// Pupils
    $pupils = PupilData::where(function($q) use ($query) {
            $q->where('FirstName', 'LIKE', '%' . $query . '%')
              ->orWhere('Surname', 'LIKE', '%' . $query . '%');
        })
        ->limit(5)
        ->get();

    // Map the results to ensure the JS can read the keys correctly
    $results = $pupils->map(function($p) {
        return [
            'type'  => 'Pupil',
            'label' => $p->FirstName . ' ' . $p->Surname,
            'url'   => route('pupil.scores.overview', $p->id),
        ];
    })->values()->all();

    // Users (admins only)
    if ($user->user_type <= 2) {
        $users = UserData::where('FirstName', 'like', "%$query%")
            ->orWhere('Surname', 'like', "%$query%")
            ->limit(5)
            ->get();

        foreach ($users as $u) {
            $results[] = [
                'type' => 'User',
                'label' => $u->FirstName . ' ' . $u->Surname,
                'url' => route('userdata.showAdminView', $u->id) 
            ];
        }
    }

    return response()->json($results);
}

}