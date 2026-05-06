@extends('layout')

@section('content')
<div class="glass-card p-4">
    <h3 class="fw-bold mb-4">Create New User</h3>

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                @error('name') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                @error('email') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>CNIC</label>
                <input type="text" name="cnic" value="{{ old('cnic') }}" class="form-control" placeholder="35202-1234567-1">
                @error('cnic') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6">
                <label>Telephone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" class="form-control" placeholder="0300-1234567">
                @error('telephone') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label>Comments</label>
                <textarea name="comments" class="form-control" rows="4">{{ old('comments') }}</textarea>
                @error('comments') <small class="text-warning">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" accept="image/*" onchange="previewImage(event)">
                @error('profile_picture') <small class="text-warning">{{ $message }}</small> @enderror

                <img id="preview" class="profile-img mt-3 d-none">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="premium-btn">
                <i class="bi bi-check-circle"></i> Register User
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