@extends('layouts.app')

@section('content')
<div class="container">
    @if ($bookmarks->isEmpty())
        <div class="row justify-content-center mt-4 align-self-center">
            <div class="col-md-10 mt-4">
                <h4>You do not have any bookmarks yet!</h4>
                <p class="mt-4">
                    <a class="btn btn-secondary" href="/bookmarks/create">Create One!</a>
                </p>
            </div>
        </div>
    @else
        @foreach ($bookmarks as $bookmark)
        <div class="row justify-content-center mt-4 align-self-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>{{ $bookmark->name }}</h2>
                                <p>{{ $bookmark->url }}</p>
                            </div>
                            <div class="col-md-4">
                                <a href="/bookmarks/edit/{{ $bookmark->id }}" class="btn btn-secondary mr-3">Edit</a>
                                <a href="/bookmarks/delete/{{ $bookmark->id }}" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
