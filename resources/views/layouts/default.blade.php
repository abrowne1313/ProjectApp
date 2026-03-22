<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'layouts.header')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    @include('layouts.header')

    <main style="padding: 20px;">
        @yield('content')
    </main>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    <!-- <script src="{{ asset('js/live-search.js') }}"></script> -->

    @yield('scripts')

    <script>
// 1. Immediate Test: If you don't see this popup, the script isn't loading at all
// alert('Script is active!'); 

document.addEventListener('input', function (e) {
    // 2. We use a global listener so it doesn't matter when the input appears
    if (e.target && e.target.id === 'live-search') {
        const searchInput = e.target;
        const resultsBox = document.getElementById('search-results');
        let query = searchInput.value.trim();

        if (query.length < 2) {
            resultsBox.style.display = 'none';
            return;
        }

        console.log('Fetching results for:', query);

        fetch("{{ route('live.search') }}?q=" + encodeURIComponent(query), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            resultsBox.innerHTML = '';
            if (data.length > 0) {
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-item';
                    div.innerHTML = `<a href="${item.url}"><span class="type" style="float:right; font-size:10px;">${item.type}</span><strong>${item.label}</strong></a>`;
                    resultsBox.appendChild(div);
                });
                resultsBox.style.display = 'block';
            } else {
                resultsBox.innerHTML = '<div class="p-2">No results found</div>';
                resultsBox.style.display = 'block';
            }
        })
        .catch(err => console.error('Error:', err));
    }
});
</script>
</body>
</html>
