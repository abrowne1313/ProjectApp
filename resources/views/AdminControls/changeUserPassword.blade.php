@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
        <div class="card-header bg-dark text-white p-3">
            <h1 class="h5 mb-0">
                {{ $user->id ? 'Reset Password for ' . $user->FirstName : 'Manual Password Reset' }}
            </h1>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('ChangeUserPassword.submit') }}" method="post">
                @csrf
                
                @if($user->id)
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="mb-3">
                        <label class="form-label text-muted small">User Email (Read Only)</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->UserEmail }}" readonly>
                    </div>
                @else
                    <div class="mb-3">
                        <label for="UserEmail" class="form-label">Enter User Email</label>
                        <input type="email" id="UserEmail" name="UserEmail" class="form-control" required>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="newpassword1" class="form-label">New Password</label>
                    <input type="password" name="newpassword1" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="newpassword2" class="form-label">Confirm New Password</label>
                    <input type="password" name="newpassword2" class="form-control" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                    <a href="{{ url()->previous() }}" class="btn btn-link btn-sm text-muted">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection