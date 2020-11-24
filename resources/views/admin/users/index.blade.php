@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-10 mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">User Accounts</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-10 mt-4">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <h2>User Accounts</h2>
            <hr>
        </div>
    </div>
    @foreach ($users as $user)
        <div class="row justify-content-center mt-4 align-self-center mb-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 my-auto user-detail">
                                <h5 class="name">{{ $user->name }}</h5>
                                <p class="email">
                                    {{ $user->email }}
                                </p>
                            </div>
                            <div class="col-md-4 justify-content-center my-auto text-center">
                                <a href="/admin/users/{{ $user->id }}/edit/" class="btn btn-secondary mr-3"  data-user-id="{{ $user->id }}">
                                    Edit
                                </a>
                                <a href="/admin/users/{{ $user->id }}" class="btn btn-danger confirm-user-delete" data-user-id="{{ $user->id }}">
                                    Delete
                                </a>
                                <form id="delete-user-form-{{ $user->id }}" class="delete-user-form" action="{{ route('users.destroy', $user) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection