<header class="topbar">
    <nav class="nav">
@auth

    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="nav-link">←</a>

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>

    <div class="nav-right">
        
        <!-- Search bar -->
        <div class="nav-search-wrapper">
            <input
                type="text"
                id="live-search"
                placeholder="Search pupils{{ auth()->user()->user_type <= 2 ? ' or users' : '' }}..."
                autocomplete="off"
            >
            <div id="search-results" class="search-results"></div>
        </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search');
    const resultsBox = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        let query = this.value;

        if (query.length < 2) {
            resultsBox.style.display = 'none';
            return;
        }

        fetch(`/live-search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsBox.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        div.innerHTML = `
                            <a href="${item.url}" style="text-decoration:none; color:inherit; display:block; padding:10px;">
                                <small class="text-primary" style="font-size: 0.7rem; text-transform:uppercase;">${item.type}</small>
                                <div style="font-weight:bold;">${item.label}</div>
                            </a>
                        `;
                        resultsBox.appendChild(div);
                    });
                    resultsBox.style.display = 'block';
                } else {
                    resultsBox.innerHTML = '<div style="padding:10px;" class="text-muted">No results found</div>';
                    resultsBox.style.display = 'block';
                }
            });
    });

    // Hide search results if clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.style.display = 'none';
        }
    });
});
</script>
        <a href="{{ route('userdata.show') }}" class="nav-link">User Settings</a>

        @if(in_array(auth()->user()->user_type, [1, 2, 3]))
            <a href="{{ route('subject.overview') }}" class="nav-link">Subject Controls</a>
        @endif

        @if(in_array(auth()->user()->user_type, [1, 2]))
            <a href="{{ route('AdminControls') }}" class="nav-link">Admin Controls</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

@endauth

@guest
    <a href="{{ route('login') }}" class="nav-link">Login</a>
@endguest

    </nav>
</header>
