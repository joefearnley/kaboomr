@extends('layouts.app')

@section('content')
<div class="container">
@if ($errors->any())
<div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form id="edit-user-form" action="{{ route('users.update', $user) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="name" aria-describedby="nameHelp">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" value="{{ $user->email }}" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="is_active" type="checkbox" id="is-active" {{ $user->is_active ? 'checked="checked"' : '' }}>
                            Active?
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" name="is_admin" type="checkbox" id="is-admin" {{ $user->is_admin ? 'checked="checked"' : '' }}>
                            Administrator?
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-info mr-2">
                    Save
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
