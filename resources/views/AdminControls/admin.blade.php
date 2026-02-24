@extends('layouts.default')


@section('title', 'Administrator Settings')

@section('content')  
<h1>Administrator Tools</h1>
<div class="admin-grid">
<a href="{{ route('user.manager') }}" class="admin-tile">
    <img src="{{ asset('images/user_manager_icon.png') }}"
         alt="User Manager"
         class="admin-icon">


</a>
<a href="{{ route('pupil.manager') }}" class="admin-tile">
    <img src="{{ asset('images/pupil_manager_icon.png') }}"
         alt="Pupil Manager"
         class="admin-icon">

</a>

</div>
<div class="admin-grid">
<a href="{{ route('class.manager') }}" class="admin-tile">
    <img src="{{ asset('images/class_manager_icon.png') }}"
         alt="Class Manager"
         class="admin-icon">

</a>

<a href="{{ route('subject.manager') }}" class="admin-tile">
    <img src="{{ asset('images/subject_manager_icon.png') }}"
         alt="Subject Manager"
         class="admin-icon">

</a>


</div>
</a>
@endsection
