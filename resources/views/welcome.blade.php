@extends('layouts.app')

@section('content')
<div class=" h-100 d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center align-self-center">
            <h1 class="display-6">Kaboomr - your bookmarks are here.</h1>
        </div>
        <div class="row justify-content-center align-self-center">
            <div class="d-flex align-items-center justify-content-center mt-4">
                <div>
                    <a href="{{ route('login') }}" class="mr-5">
                        Log in
                    </a>
                </div>
                <div>
                    <a href="{{ route('register') }}" class="">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
