@extends('layouts.app')

@section('content')
<div class="container">
    @if ($bookmarks->isEmpty())
        <div class="row justify-content-center mt-4 align-self-center">
            <div class="col-md-10 mt-4">
                <h4>No bookmarks tagged with &quot;{{ $tag }}&quot;.</h4>
                <p class="mt-4">
                    <a class="btn btn-secondary" href="/bookmarks/create">Create One!</a>
                </p>
            </div>
        </div>
    @else
        <div class="row mt-4">
        <div class="col-md-5 offset-md-1 my-2 text-right">
                <h4>Bookmarks tagged with &quot;{{ $tag }}&quot;.</h4>
            </div>
            <div class="col-md-5 my-2 text-right">
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
