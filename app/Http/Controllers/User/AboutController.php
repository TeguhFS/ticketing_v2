<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;

class AboutController extends Controller
{
    public function index()
    {
        $sections = AboutSection::getAllActive()->keyBy('key');

        $hero    = $sections->get('hero');
        $stats   = $sections->get('stats');
        $vision  = $sections->get('vision');
        $mission = $sections->get('mission');
        $values  = $sections->get('values');
        $team    = $sections->get('team');
        $cta     = $sections->get('cta');

        return view('user.about.index', compact(
            'hero',
            'stats',
            'vision',
            'mission',
            'values',
            'team',
            'cta'
        ));
    }
}
