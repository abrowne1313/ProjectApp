@extends('layouts.default')

@section('content')
<div class="container">

    <h1>Edit Scheme – {{ $scheme->subject->Subject }} (Year {{ $scheme->YearGroup }})</h1>

   
    <form method="POST" action="{{ route('schemes.update', $scheme->id) }}">
        @csrf
        @method('PUT')

        <h2>Topics</h2>
        <p>Drag to reorder. Edit names and max scores directly.</p>

        <ul id="topic-list" class="list-group">

            @foreach ($scheme->topics as $topic)
            <li class="list-group-item d-flex align-items-center flex-nowrap" data-id="{{ $topic->id }}">

                <div class="d-flex align-items-center flex-shrink-1">
                    <span class="handle me-3" style="cursor: grab;">☰</span>

                <input type="text"
                     name="topics[{{ $topic->id }}][Title]"
                     value="{{ $topic->Title }}"
                     class="form-control me-3"
                     style="width: 40%; min-width: 200px;">

                <input type="number"
                     name="topics[{{ $topic->id }}][MaxTestScore]"
                     value="{{ $topic->MaxTestScore }}"
                     class="form-control me-3"
                     style="width: 20%; min-width: 100px;">

               <input type="hidden"
                      name="topics[{{ $topic->id }}][TeachingOrder]"
                      class="order-field"
                      value="{{ $topic->TeachingOrder }}">
                </div>

                {{-- DELETE BUTTON (triggers hidden form below main form) --}}
                <button type="button"
                        class="btn btn-outline-danger btn-sm ms-3"
                        onclick="document.getElementById('delete-topic-{{ $topic->id }}').submit()">
                    <i class="bi bi-trash"></i>
                </button>

            </li>
            @endforeach

        </ul>

        <button type="button" onclick="addTopic()" class="btn btn-secondary mt-3">Add Topic</button>
        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>

    </form>


    @foreach ($scheme->topics as $topic)
        <form id="delete-topic-{{ $topic->id }}"
              action="{{ route('scheme.topic.delete', $topic->id) }}"
              method="POST"
              style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

</div>
@endsection



<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const list = document.getElementById('topic-list');

    new Sortable(list, {
        handle: '.handle',
        animation: 150,
        onEnd: function () {
            document.querySelectorAll('#topic-list li').forEach((li, index) => {
                li.querySelector('.order-field').value = index + 1;
            });
        }
    });
});
</script>


<script>
let newTopicIndex = 0;

function addTopic() {
    const list = document.getElementById('topic-list');

    list.insertAdjacentHTML(
        'beforeend',
        `
        <li class="list-group-item d-flex align-items-center flex-nowrap" data-id="new-${newTopicIndex}">
            
            <div class="d-flex align-items-center flex-shrink-1">
                <span class="handle me-3" style="cursor: grab;">☰</span>

                <input type="text"
                       name="new_topics[${newTopicIndex}][Title]"
                       class="form-control me-3"
                       placeholder="New topic"
                       style="width: 40%; min-width: 200px;">

                <input type="number"
                       name="new_topics[${newTopicIndex}][MaxTestScore]"
                       class="form-control me-3"
                       placeholder="Max score"
                       style="width: 20%; min-width: 100px;">

                <input type="hidden"
                       name="new_topics[${newTopicIndex}][TeachingOrder]"
                       class="order-field"
                       value="${list.children.length + 1}">
            </div>

            <button type="button"
                    class="btn btn-outline-danger btn-sm delete-topic ms-3">
                <i class="bi bi-trash"></i>
            </button>

        </li>
        `
    );

    newTopicIndex++;
}
</script>


<script>
document.addEventListener("click", function (e) {
    if (e.target.closest(".delete-topic")) {
        const li = e.target.closest("li");
        li.remove();

        // Recalculate teaching order
        document.querySelectorAll('#topic-list li').forEach((li, index) => {
            li.querySelector('.order-field').value = index + 1;
        });
    }
});
</script>
