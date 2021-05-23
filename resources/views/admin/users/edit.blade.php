@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-10 mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/users">User Accounts</a></li>
                    <li class="breadcrumb-item">Edit User Account</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">
                        {{ __('Edit User Account') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form id="edit-user-form" action="{{ route('users.update', $user) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="name" aria-describedby="nameHelp">
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" value="{{ $user->email }}" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="is_active" type="checkbox" id="is-active" {{ $user->is_active ? 'checked="checked"' : '' }}>
                                    Active?
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="is_admin" type="checkbox" id="is-admin" {{ $user->is_admin ? 'checked="checked"' : '' }}>
                                    Administrator?
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            Save
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
