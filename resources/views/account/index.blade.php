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
            <h2>Account</h2>
            <hr>
        </div>
    </div>
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form id="update-user-name" action="{{ route('account.update-name') }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">Update Name</label>
                    <input type="text" value="{{  Auth::user()->name }}" name="name" class="form-control" id="name" aria-describedby="nameHelp" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-secondary mr-2">
                    Update
                </button>
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-5 align-self-center">
        <div class="col-md-8 mt-4">
            <form id="update-user-email" action="{{ route('account.update-email') }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="email">Update Email Address</label>
                    <input type="text" value="{{ Auth::user()->email }}" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-secondary mr-2">
                    Update
                </button>
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-5 align-self-center">
        <div class="col-md-8 mt-4">
            <hr>
            <a href="/password/reset">Reset Password</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/bookmarks.js') }}" defer></script>
@endsection
