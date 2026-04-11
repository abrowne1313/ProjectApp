<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use App\Models\PupilData;

class SearchController extends Controller
{

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

    // Link results of pupil search to scores overview
    $results = $pupils->map(function($p) {
        return [
            'type'  => 'Pupil',
            'label' => $p->FirstName . ' ' . $p->Surname,
            'url'   => route('pupil.scores.overview', $p->id),
        ];
    })->values()->all();
    // Users (admins only)- links to userdata adminview for edits and password resets
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