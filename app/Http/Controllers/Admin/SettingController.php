<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'contact_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'maintenance_mode' => ['sometimes', 'in:0,1'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('status', __('Settings saved.'));
    }
}
