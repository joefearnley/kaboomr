@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Edit User Account</h5>
                </div>
                <div class="card-body">
                    <form id="update-user-name" action="{{ route('account.update') }}" method="post">
                        @csrf
                        @method('PATCH')
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
                            <label for="email">Email Address</label>
                            <input type="text" value="{{ $user->email }}" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5 align-self-center">
        <div class="col-md-8 mt-4">
            <a class="btn btn-primary" href="/password/reset">Reset Password</a>
        </div>
    </div>
</div>
@endsection