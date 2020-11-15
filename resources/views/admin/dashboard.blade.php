@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-10 mt-4">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <h2>Admin Dashboard</h2>
            <hr>
        </div>
    </div>
    <div class="row justify-content-center mt-4 align-self-center mb-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 my-auto">
                            <a href="/admin/accounts">
                                <h5>Users</h5>
                            </a>
                        </div>
                        <div class="col-md-4 justify-content-center my-auto text-center">
                            <a href="/admin/accounts/create" class="btn btn-secondary mr-3">
                                Add User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection