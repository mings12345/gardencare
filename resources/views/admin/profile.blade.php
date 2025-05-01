@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="header">
        <h1>My Profile</h1>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="avatar" name="avatar">
                    @if($user->avatar)
                        <div class="mt-2">
                            <img src="{{ Storage::url($user->avatar) }}" alt="Profile Picture" width="100" class="img-thumbnail">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection