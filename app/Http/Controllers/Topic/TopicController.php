<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Topic;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index()
    {
        // show only soft deleted
        if (request()->has('trashed')) {
            $topics = Topic::onlyTrashed()->get();
        } else {
            $topics = Topic::all();
        }

        return Inertia::render('Topic/TopicIndex', [
            'topics' => $topics,
        ]);
    }

    public function create()
    {
        $slug = \App\Helpers\MakeShortCode::make();

        return Inertia::render('Topic/TopicCreate', [
            'slug' => $slug,
        ]);
    }

    public function store(TopicRequest $request)
    {

        Topic::create($request->validated());

        return redirect()->route('topic.index');
    }

    public function edit(Topic $topic)
    {
        return Inertia::render('Topic/TopicEdit', [
            'topic' => $topic,
        ]);
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());

        return redirect()->route('topic.index');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('topic.index');
    }
}
