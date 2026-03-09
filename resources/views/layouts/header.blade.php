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
