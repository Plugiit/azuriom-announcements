@extends('admin.layouts.admin')

@section('title', trans('plugiit-announcements::admin.announcements.edit', ['title' => $announcement->title]))

@section('content')
    <form action="{{ route('plugiit-announcements.admin.announcements.update', $announcement) }}" method="POST">
        @method('PUT')

        @include('plugiit-announcements::admin.announcements._form')

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
            </button>
            <a href="{{ route('plugiit-announcements.admin.announcements.destroy', $announcement) }}" class="btn btn-danger" data-confirm="delete">
                <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
            </a>
        </div>
    </form>
@endsection
