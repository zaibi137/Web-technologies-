@extends('layout')

@section('content')
<div class="glass-card p-4">
    <h3 class="fw-bold mb-4">Edit User</h3>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                @error('name') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                @error('email') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>CNIC</label>
                <input type="text" name="cnic" value="{{ old('cnic', $user->cnic) }}" class="form-control">
                @error('cnic') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>Telephone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="form-control">
                @error('telephone') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label>Comments</label>
                <textarea name="comments" class="form-control" rows="4">{{ old('comments', $user->comments) }}</textarea>
                @error('comments') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" accept="image/*" onchange="previewImage(event)">
                @error('profile_picture') <small class="text-warning">{{ $message }}</small> @enderror

                @if($user->profile_picture)
                    <img id="preview" src="{{ asset('uploads/' . $user->profile_picture) }}" class="profile-img mt-3">
                @else
                    <img id="preview" class="profile-img mt-3 d-none">
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="premium-btn">
                <i class="bi bi-save"></i> Update User
            </button>

            <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4">Back</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        let preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.classList.remove('d-none');
    }
</script>
@endsection