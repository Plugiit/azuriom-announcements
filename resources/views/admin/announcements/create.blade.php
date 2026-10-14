@extends('admin.layouts.admin')

@section('title', trans('plugiit-announcements::admin.announcements.create'))

@section('content')
    <form action="{{ route('plugiit-announcements.admin.announcements.store') }}" method="POST">
        @include('plugiit-announcements::admin.announcements._form')

        <button type="submit" class="btn btn-primary mb-4">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>
@endsection
