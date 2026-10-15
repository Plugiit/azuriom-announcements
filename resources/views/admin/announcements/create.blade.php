@extends('admin.layouts.admin')

@section('title', trans('announcements::admin.announcements.create'))

@section('content')
    <form action="{{ route('announcements.admin.announcements.store') }}" method="POST">
        @include('announcements::admin.announcements._form')

        <button type="submit" class="btn btn-primary mb-4">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>
@endsection
