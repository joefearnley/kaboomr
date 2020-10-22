@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4 align-self-center">
        <div class="col-md-8 mt-4">
            <form method="POST" action="{{ route('bookmarks.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" aria-describedby="nameHelp" required>
                    <small id="nameHelp" class="form-text text-muted">Please enter a name</small>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" class="form-control" id="url" aria-describedby="urlHelp" required>
                    <small id="urlHelp" class="form-text text-muted">Please enter a valid URL.</small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-secondary">Create</button>
                <a href="/bookmarks" class="btn btn-primary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection