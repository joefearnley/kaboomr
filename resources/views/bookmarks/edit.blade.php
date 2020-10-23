@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form action="{{ route('bookmarks.update', $bookmark) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" value="{{ $bookmark->name }}" name="name" class="form-control" id="name" aria-describedby="nameHelp" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" value="{{ $bookmark->url }}" name="url" class="form-control" id="url" aria-describedby="urlHelp" required>
                    @error('url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3">{{ $bookmark->description }}</textarea>
                </div>
                <button type="submit" class="btn btn-secondary mr-2">
                    Save
                </button>
                <a href="/bookmarks" class="btn btn-primary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection