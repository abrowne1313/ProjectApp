<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use App\Models\Schemes;
use App\Models\revisionLists;
use Illuminate\Http\Request;

class TopicsController extends Controller
{




public function show($id) {
    $topic = Topics::with([
        'scheme.subject', // load scheme + subject 
         // load subtopics 
        ])->findOrFail($id);

        return view('HoDControls.TopicView', compact('topic')); 
        }

}
