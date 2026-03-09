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
        return view('revisionlists.show', [
            'topic' => $topic,
            'revisionlist' => $topic->revisionlist
        ]);
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
    public function storeOrUpdate(Request $request, Topics $topic)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        // Create or update the revision list
        $topic->revisionlist()->updateOrCreate(
            ['topic_id' => $topic->id],
            ['content' => $request->content]
        );

        return redirect()
            ->route('topic.show', $topic->id)
            ->with('success', 'Revision list saved successfully.');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(revisionLists $revisionLists)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, revisionLists $revisionLists)
    {
        //
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
            ->route('revisionlist.show', $topic->id)
            ->with('success', 'Revision list deleted.');
    }
}
