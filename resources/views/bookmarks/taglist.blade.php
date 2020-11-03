@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-md-6 offset-md-1 my-2">
            <h4>Bookmarks tagged with &quot;{{ $tag }}&quot;.</h4>
        </div>
        <div class="col-md-4 my-2 text-right">
            <a href="{{ route('bookmarks.create') }}" class="btn btn-secondary">
                {{ __('Create Bookmark') }}
            </a>
        </div>
    </div>
    @if ($bookmarks->isEmpty())
        <div class="row justify-content-center mt-4 align-self-center">
            <div class="col-md-10 mt-4">
                <hr>
                <h4>No bookmarks tagged with &quot;{{ $tag }}&quot;.</h4>
            </div>
        </div>
    @else
        @foreach ($bookmarks as $bookmark)
        <div class="row justify-content-center mt-4 align-self-center mb-5">
            <div class="col-md-10">
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
                            <div class="col-md-4 justify-content-center my-auto text-center">
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
