<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'app_name' => Setting::get('app_name', config('app.name')),
            'contact_email' => Setting::get('contact_email', ''),
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
        ];

        return view('admin.settings.index', ['settings' => $settings]);
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            Setting::set($key, $value);
        }

        app(ActivityLogService::class)->log($request->user(), 'setting.updated', null, [
            'keys' => array_keys($request->validated()),
        ]);

        return redirect()->route('admin.settings.index')->with('status', __('Settings saved.'));
    }
}
