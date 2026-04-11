@extends('layouts.default')

@section('content')
<div class="container pb-5">
    <div class="mb-4 pb-2 border-bottom">
        <h1 class="h3 text-primary">
            {{ $topic->scheme->subject->Subject }} – Year {{ $topic->scheme->YearGroup }}
        </h1>
        <h2 class="h5 text-secondary">Topic: {{ $topic->Title }}</h2>
        <span class="badge bg-info text-dark">Teaching Order: {{ $topic->TeachingOrder }}</span>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h3 class="h5 mb-0">Edit Revision List</h3>
        </div>
        <div class="card-body">
            {{-- Ensure BOTH content and URL are inside this form --}}
            <form method="POST" action="{{ route('revisionlists.save', $topic->id) }}">
                @csrf
                
                <div class="form-group mb-4">
                    <textarea name="content" id="rich-editor" class="form-control">
                        {{ old('content', $topic->revisionlist->content ?? '') }}
                    </textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold mb-2">Video/Web Resource Link (Optional)</label>
                    <div class="input-group">
                        <span class="input-group-text">URL</span>
                        <input type="url" name="url" class="form-control" 
                               placeholder="https://www.youtube.com/watch?v=..." 
                               value="{{ old('url', $topic->revisionlist->url ?? '') }}">
                    </div>
                    <small class="text-muted">If a link is provided, a QR code will automatically be added to the pupil's revision list.</small>
                </div>

                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-save"></i> Save Revision List
                </button>
            </form>
        </div>
    </div>
</div>

{{-- TinyMCE Script --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#rich-editor',
        height: 400,
        menubar: false, {{-- Hiding menubar makes it feel 'tighter' --}}
        plugins: 'lists link code wordcount',
        toolbar: 'undo redo | bold italic | bullist numlist | link | removeformat code',
        setup: function (editor) {
        editor.on('change', function () {
        editor.save(); {{-- Forces sync with the textarea --}}
        });
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
</script>
@endsection