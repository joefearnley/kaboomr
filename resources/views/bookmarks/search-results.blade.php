@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-md-12 offset-xl-1 my-2">
            <h4>Search results found for &quot;{{ $term }}&quot;.</h4>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4 offset-xl-1">
            <div class="form-inline my-2 input-group">
                <input id="search-input" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button id="search-button" class="btn btn-primary my-2 my-sm-0" type="submit">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="col-md-8 col-xl-6 my-2 d-none d-md-block text-right">
            <a href="{{ route('bookmarks.create') }}" class="btn btn-primary">
                {{ __('Create Bookmark') }}
            </a>
        </div>
    </div>
    @if ($bookmarks->isEmpty())
        <div class="row mt-4">
            <div class="col-xl-10 mt-4">
                <hr>
                <h4>No results found for &quot;{{ $term }}&quot;.</h4>
            </div>
        </div>
    @else
        @foreach ($bookmarks as $bookmark)
        <div class="row justify-content-center mt-4">
            <div class="col-md-12 col-xl-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 my-auto bookmark-detail">
                                <h5 class="title">{{ $bookmark->name }}</h5>
                                <p class="url">
                                    <a href="{{ $bookmark->url }}" target="_blank">
                                        {{ $bookmark->url }}
                                    </a>
                                </p>
                                <p class="description">{{ $bookmark->description }}</p>
                            </div>
                            <div class="col-md-4 my-auto text-lg-center">
                                <a href="/bookmarks/{{ $bookmark->id }}/edit/" class="btn btn-primary mr-3"  data-bookmark-id="{{ $bookmark->id }}">
                                    Edit
                                </a>
                                <a href="/bookmarks/{{ $bookmark->id }}/delete/" class="btn btn-danger confirm-bookmark-delete" data-bookmark-id="{{ $bookmark->id }}">
                                    Delete
                                </a>
                                <form id="delete-bookmark-form-{{ $bookmark->id }}" class="delete-bookmark-form" action="{{ route('bookmarks.destroy', $bookmark) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @if (!$bookmark->tags->isEmpty())
                        <div class="card-footer bg-transparent tags">
                        @foreach ($bookmark->tags as $tag)
                            <a href="/bookmarks/tag/{{ $tag->slug }}" class="badge badge-light mr-2">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $bookmarks->links() }}
        </div>
    @endif
</div>
@endsection
