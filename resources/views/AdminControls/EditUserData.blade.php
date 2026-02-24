@extends('layouts.default')

@section('title', 'Edit User')

@section('content')
<h1>Edit User</h1>

<label>Search User</label>
<input type="text" id="user-search" placeholder="Search by name or email" autocomplete="off">

<ul id="search-results"></ul>

<hr>

@if(isset($user))
<form method="POST" action="{{ route('userdata.update', $user->id) }}">
    @csrf
    @method('PUT')

    <label>First Name</label>
    <input type="text" name="FirstName" value="{{ $user->FirstName }}" required>

    <label>Surname</label>
    <input type="text" name="Surname" value="{{ $user->Surname }}" required>

    <label>Email</label>
    <input type="email" name="UserEmail" value="{{ $user->UserEmail }}" required>

    <label>User Type</label>
    <select name="user_type" required>
        <option value="1" {{ $user->user_type == 1 ? 'selected' : '' }}>Application Admin</option>
        <option value="2" {{ $user->user_type == 2 ? 'selected' : '' }}>Centre Admin</option>
        <option value="3" {{ $user->user_type == 3 ? 'selected' : '' }}>HoD</option>
        <option value="4" {{ $user->user_type == 4 ? 'selected' : '' }}>Teacher</option>
    </select>

    <button type="submit">Update User</button>
</form>
@endif
   

@section('scripts')
<script>
console.log(document.getElementById('user-search'));


document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('user-search');
    const results = document.getElementById('search-results');

    input.addEventListener('keyup', function () {
        const query = this.value.trim();

        if (query.length < 2) {
            results.innerHTML = '';
            return;
        }

        fetch(`{{ route('userdata.liveSearch') }}?q=${query}`)
            .then(res => res.json())
            .then(data => {
                results.innerHTML = '';

                if (data.length === 0) {
                    results.innerHTML = '<li>No users found</li>';
                    return;
                }

                data.forEach(user => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <a href="/admin/users/${user.id}/edit">
                            ${user.FirstName} ${user.Surname}
                            <small>(${user.UserEmail})</small>
                        </a>
                    `;
                    results.appendChild(li);
                });
            });
    });
});
</script>
@endsection
