<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CookiePolicyController extends Controller
{
    public $cookies = [
        'usage' => [
            ['name' => '_ga', 'purpose' => 'Used to distinguish users', 'expires' => '2 years',],
            ['name' => '_gat', 'purpose' => 'Used to throttle request rate', 'expires' => '10 minutes',],
            ['name' => '_gcl_au', 'purpose' => 'Used by Google AdSense for experimenting with advertisement efficiency across websites using their services', 'expires' => '3 months',],
            ['name' => '_gid', 'purpose' => 'Used to distinguish users', 'expires' => '24 hours',],
            ['name' => '_gac', 'purpose' => 'Contains campaign related information for the user', 'expires' => '90 days',],
        ],
        'essential' => [
            ['name' => 'XSRF-TOKEN', 'purpose' => 'A standard cookie used to prevent a malicious exploit of a website', 'expires' => '2 hours'],
            ['name' => 'admr_session', 'purpose' => 'Holds session data to complete the application', 'expires' => 'When you close your browser'],
            ['name' => 'cookies_preference_set', 'purpose' => 'Registers the input cookie preference', 'expires' => 'When you close your browser'],
            ['name' => 'cookies_policy', 'purpose' => 'Register the response to cookies permission question', 'expires' => 'When you close your browser'],
        ],
        'settings' => [],
        'campaigns' => [],
    ];

    public function callAction($method, $parameters)
    {
        $historic_entry = session('site_history');

        if (request()->headers->get('referer')) {
            $current = parse_url(request()->url());
            $referer = parse_url(request()->headers->get('referer'));
            $current_url = $current['host'] .'/' . $current['path'];
            $referer_url = $referer['host'] .'/' . $referer['path'];

            if ($current_url != $referer_url) {
                if ($current['host'] == $referer['host']) {
                    session()->flash('site_history', request()->headers->get('referer'));
                }
            } else {
                session()->flash('site_history', $historic_entry);
            }
        } else {
            session()->forget('site_history');
        }

        return parent::callAction($method, $parameters);
    }

    public function index()
    {
        return view('cookie-policy', [
            'cookies' => $this->cookies,
            'back_link' => session('site_history', false)
        ]);
    }

    public function save()
    {
        session()->flash('flash', 'Your cookie settings were saved');
        return redirect()->route('cookie-policy');
    }
}
