<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use App\Models\Schemes;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function storeSubtopic(Request $request, $id) 
{ 
    $request->validate([ 
        'Title' => 'required|string|max:255' 
        ]); 
        
        $topic = Topics::findOrFail($id); 
        
        $topic->subtopics()->create([ 
            'Title' => $request->Title 
            ]); 
            
            return redirect()->route('topic.show', $id) 
            ->with('success', 'Subtopic added successfully.'); 
            }
    /**
     * Display the specified resource.
     */

public function show($id) {
    $topic = Topics::with([
        'scheme.subject', // load scheme + subject 
        'subtopics' // load subtopics 
        ])->findOrFail($id);

        return view('HoDControls.TopicView', compact('topic')); 
        }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topics $topics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topics $topics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topics $topics)
    {
        //
    }
}
