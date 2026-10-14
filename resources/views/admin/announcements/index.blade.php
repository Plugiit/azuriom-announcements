@extends('admin.layouts.admin')

@section('title', trans('plugiit-announcements::admin.announcements.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <p class="text-body-secondary">{{ trans('plugiit-announcements::admin.announcements.info') }}</p>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.fields.title') }}</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.fields.type') }}</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.fields.period') }}</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.targeting') }}</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.fields.priority') }}</th>
                        <th scope="col">{{ trans('plugiit-announcements::admin.announcements.fields.status') }}</th>
                        <th scope="col">{{ trans('messages.fields.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($announcements as $announcement)
                        @php($status = $announcement->status())
                        <tr>
                            <th scope="row">{{ $announcement->id }}</th>
                            <td>
                                @php($swatch = ['info' => '#2563eb', 'success' => '#15803d', 'warning' => '#f59e0b', 'danger' => '#c81e1e'][$announcement->style] ?? ($announcement->color ?? '#5b5bd6'))
                                <span class="d-inline-block rounded-circle me-1 align-middle" style="width: .75rem; height: .75rem; background: {{ $swatch }}" aria-hidden="true"></span>
                                {{ $announcement->title }}
                            </td>
                            <td>
                                <i class="bi bi-{{ $announcement->type === 'popup' ? 'window-stack' : 'layout-text-window' }}"></i>
                                {{ trans('plugiit-announcements::admin.announcements.types.'.$announcement->type) }}
                            </td>
                            <td class="small">
                                {{ $announcement->starts_at ? format_date($announcement->starts_at, true) : trans('plugiit-announcements::admin.announcements.no_start') }}
                                <i class="bi bi-arrow-right"></i>
                                {{ $announcement->ends_at ? format_date($announcement->ends_at, true) : trans('plugiit-announcements::admin.announcements.no_end') }}
                            </td>
                            <td class="small">
                                @if($announcement->audience === 'roles')
                                    {{ collect($announcement->roles)->map(fn ($id) => $roles[$id] ?? '#'.$id)->implode(', ') }}
                                @else
                                    {{ trans('plugiit-announcements::admin.announcements.audiences.'.$announcement->audience) }}
                                @endif
                                <br>
                                <span class="text-body-secondary">
                                    {{ trans('plugiit-announcements::admin.announcements.pages_options.'.$announcement->pages) }}
                                    @if($announcement->pages === 'pattern')
                                        : <code>{{ Str::limit(str_replace(["\r\n", "\n"], ', ', $announcement->page_patterns), 40) }}</code>
                                    @endif
                                </span>
                            </td>
                            <td>{{ $announcement->priority }}</td>
                            <td>
                                <span class="badge bg-{{ ['active' => 'success', 'scheduled' => 'info', 'expired' => 'secondary', 'disabled' => 'danger'][$status] }}">
                                    {{ trans('plugiit-announcements::admin.announcements.statuses.'.$status) }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('plugiit-announcements.admin.announcements.edit', $announcement) }}" class="mx-1" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ route('plugiit-announcements.admin.announcements.destroy', $announcement) }}" class="mx-1" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-body-secondary">{{ trans('plugiit-announcements::admin.announcements.empty') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $announcements->links() }}

            <a class="btn btn-primary" href="{{ route('plugiit-announcements.admin.announcements.create') }}">
                <i class="bi bi-plus-lg"></i> {{ trans('plugiit-announcements::admin.announcements.create') }}
            </a>
        </div>
    </div>
@endsection
