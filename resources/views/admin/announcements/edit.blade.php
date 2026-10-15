@extends('admin.layouts.admin')

@section('title', trans('announcements::admin.announcements.edit', ['title' => $announcement->title]))

@section('content')
    <form action="{{ route('announcements.admin.announcements.update', $announcement) }}" method="POST">
        @method('PUT')

        @include('announcements::admin.announcements._form')

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
            </button>
            <a href="{{ route('announcements.admin.announcements.destroy', $announcement) }}" class="btn btn-danger" data-confirm="delete">
                <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
            </a>
        </div>
    </form>
@endsection
