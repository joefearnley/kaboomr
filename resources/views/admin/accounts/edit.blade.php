@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form id="edit-user-form" action="{{ route('admin.account.update', $user) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="name" aria-describedby="nameHelp" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" value="{{ $user->email }}" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_admin" class="form-check-input" id="is-admin">
                    <label class="form-check-label" for="is-admin">Administrator?</label>
                </div>
                <button type="submit" class="btn btn-secondary mr-2">
                    Save
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
