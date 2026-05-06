@php use Illuminate\Support\Str; @endphp

@extends('layout')

@section('content')
<div class="glass-card p-4">

    <form action="{{ route('users.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-9">
            <input type="email" name="search" value="{{ $search }}" class="form-control"
                   placeholder="Search user by email...">
        </div>

        <div class="col-md-3 d-grid">
            <button class="premium-btn">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Registered Users</h4>
        <span class="badge bg-light text-dark rounded-pill">
            Total: {{ $users->total() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>CNIC</th>
                    <th>Telephone</th>
                    <th>Comments</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            @if($user->profile_picture)
                                <img src="{{ asset('uploads/' . $user->profile_picture) }}" class="profile-img">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="profile-img">
                            @endif
                        </td>

                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->cnic }}</td>
                        <td>{{ $user->telephone }}</td>
                        <td>{{ Str::limit($user->comments, 40) }}</td>

                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form id="delete-form-{{ $user->id }}"
                                  action="{{ route('users.destroy', $user->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        onclick="confirmDelete('delete-form-{{ $user->id }}')"
                                        class="btn btn-sm btn-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <h5>No users found</h5>
                            <p class="muted-text">Try another email or add a new user.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

</div>
@endsection