<?php

namespace Azuriom\Plugin\PlugiitAnnouncements\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Role;
use Azuriom\Plugin\PlugiitAnnouncements\Models\Announcement;
use Azuriom\Plugin\PlugiitAnnouncements\Requests\AnnouncementRequest;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('is_enabled')
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->paginate(20);

        return view('plugiit-announcements::admin.announcements.index', [
            'announcements' => $announcements,
            'roles' => Role::pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('plugiit-announcements::admin.announcements.create', [
            'roles' => Role::orderByDesc('power')->get(),
        ]);
    }

    public function store(AnnouncementRequest $request)
    {
        Announcement::create($request->announcementData());

        return to_route('plugiit-announcements.admin.announcements.index')
            ->with('success', trans('messages.status.success'));
    }

    public function edit(Announcement $announcement)
    {
        return view('plugiit-announcements::admin.announcements.edit', [
            'announcement' => $announcement,
            'roles' => Role::orderByDesc('power')->get(),
        ]);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update($request->announcementData());

        return to_route('plugiit-announcements.admin.announcements.index')
            ->with('success', trans('messages.status.success'));
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return to_route('plugiit-announcements.admin.announcements.index')
            ->with('success', trans('messages.status.success'));
    }
}
