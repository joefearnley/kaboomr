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
        <div class="row mt-4">
            <div class="col-md-4 offset-md-1 my-2">
                <h4>Bookmarks</h4>
            </div>
            <div class="col-md-4">
                <form class="form-inline my-2" action="#" method="GET">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                            <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                        </svg>
                    </button>
            </div>
            <div class="col-md-3 my-2">
                <a href="{{ route('bookmarks.create') }}" class="btn btn-secondary">
                    {{ __('Create Bookmark') }}
                </a>
            </div>
        </div>
        @foreach ($bookmarks as $bookmark)
        <div class="row justify-content-center mt-4 align-self-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 my-auto bookmark-detail">
                                <h2 class="title">{{ $bookmark->name }}</h2>
                                <p class="url">
                                    <a href="{{ $bookmark->url }}" target="_blank">
                                        {{ $bookmark->url }}
                                    </a>
                                </p>
                                <p class="description">{{ $bookmark->description }}</p>
                            </div>
                            <div class="col-md-4 justify-content-center my-auto">
                                <a href="/bookmarks/{{ $bookmark->id }}/edit/" class="btn btn-secondary mr-3">
                                    Edit
                                </a>
                                <a href="/bookmarks/{{ $bookmark->id }}/delete/" class="btn btn-danger confirm-bookmark-delete">
                                    Delete
                                </a>
                                <form id="delete-bookmark" action="{{ route('bookmarks.destroy', $bookmark) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent tags">
                    @foreach ($bookmark->tags as $tag)
                        <a href="/bookmarks/tag/{{ $tag->slug }}" class="badge badge-light mr-2">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
