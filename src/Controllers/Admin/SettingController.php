<?php

namespace Azuriom\Plugin\Announcements\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Announcements\Support\Announcements;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function show()
    {
        return view('announcements::admin.settings', [
            'barMode' => Announcements::barMode(),
            'rotationDelay' => Announcements::rotationDelay(),
        ]);
    }

    public function save(Request $request)
    {
        $validated = $this->validate($request, [
            'bar_mode' => ['required', Rule::in(Announcements::BAR_MODES)],
            'rotation_delay' => ['required', 'integer', 'min:3', 'max:60'],
        ]);

        Setting::updateSettings([
            'announcements.bar_mode' => $validated['bar_mode'],
            'announcements.rotation_delay' => $validated['rotation_delay'],
        ]);

        ActionLog::log('announcements.settings.updated');

        return to_route('announcements.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
