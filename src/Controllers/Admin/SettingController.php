<?php

namespace Azuriom\Plugin\PlugiitAnnouncements\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\PlugiitAnnouncements\Support\Announcements;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function show()
    {
        return view('plugiit-announcements::admin.settings', [
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
            'plugiit-announcements.bar_mode' => $validated['bar_mode'],
            'plugiit-announcements.rotation_delay' => $validated['rotation_delay'],
        ]);

        ActionLog::log('plugiit-announcements.settings.updated');

        return to_route('plugiit-announcements.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
