<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Skill;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $setting = Setting::first();

        $skills = Skill::orderBy('group_name')
            ->orderBy('position')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Skill $skill) => $skill->group_name ?: 'Autres');

        return view('about', compact('setting', 'skills'));
    }
}
