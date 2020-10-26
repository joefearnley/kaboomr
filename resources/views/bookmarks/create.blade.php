@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form id="create-bookmark-form" action="{{ route('bookmarks.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" id="name" aria-describedby="nameHelp" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" name="url" class="form-control" id="url" aria-describedby="urlHelp" required>
                    @error('url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <div class="input-group mb-3">
                        <input type="text" name="tags" id="add-tag-input" class="form-control" aria-label="add tag" aria-describedby="add-tag">
                        <div class="input-group-append">
                            <button type="button" id="add-tag-button" class="btn btn-outline-secondary">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                <div class="tags-input mt-2 mb-5">
                </div>
                <button type="submit" id="create-bookmark-submit-button" class="btn btn-secondary mr-2">
                    Create
                </button>
                <a href="/bookmarks" class="btn btn-primary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/bookmarks.js') }}" defer></script>
@endsection