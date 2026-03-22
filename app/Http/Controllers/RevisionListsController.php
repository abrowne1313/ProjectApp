<?php

namespace App\Http\Controllers;

use App\Models\revisionLists;
use App\Models\Topics;
use Illuminate\Http\Request;

class RevisionListsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(Topics $topic)
    {
        return view('revisionListView', [
            'topic' => $topic,
            'revisionlist' => $topic->revisionlist
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(Request $request, Topics $topic)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        // Create or update the revision list
        $topic->revisionlist()->updateOrCreate(
            ['topic_id' => $topic->id],
            ['content' => $request->content],
            ['url' => $request->url]
        );

        return redirect()
            ->route('topic.show', $topic->id)
            ->with('success', 'Revision list saved successfully.');
    }

 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topics $topic)
    {
        if ($topic->revisionlist) {
            $topic->revisionlist->delete();
        }

        return redirect()
            ->route('revisionlists.show', $topic->id)
            ->with('success', 'Revision list deleted.');
    }
}
