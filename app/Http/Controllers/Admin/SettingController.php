<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::first() ?? Setting::create([]);

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::first() ?? Setting::create([]);

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'github_url' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_subtitle' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string'],
            'about_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:8192'],
        ]);

        unset($validated['cv_file']);

        if ($request->hasFile('about_image')) {
            if ($setting->about_image) {
                Storage::disk('public')->delete($setting->about_image);
            }

            $validated['about_image'] = $request->file('about_image')->store('about', 'public');
        }

        if ($request->hasFile('cv_file')) {
            if ($setting->cv_path) {
                Storage::disk('public')->delete($setting->cv_path);
            }

            $validated['cv_path'] = $request->file('cv_file')->store('cv', 'public');
        }

        $setting->update($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}


