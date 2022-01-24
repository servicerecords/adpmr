<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceSelectRequest;
use App\Models\Application;
use App\Models\Constant;
use App\Models\ServiceBranch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
//        session(['service' => ServiceBranch::RAF]);
//        session(['serviceperson-service' => ServiceBranch::getInstance()->getName(ServiceBranch::RAF)]);

        // Application::getInstance()->markSectionComplete(Constant::SECTION_SERVICE);

        // return redirect()->route('death-in-service');

        return view('service', [
            'branches' => ServiceBranch::getInstance()->getOptionList()
        ]);
    }

    /**
     * @param ServiceSelectRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(ServiceSelectRequest $request)
    {
        if (session('service', false)) {
            if ($request->get('service') !== session('service', false)) {
                Application::getInstance()->resetCompletedSections();
                foreach (session()->all() as $sessionKey => $sessionValue) {
                    if (Str::startsWith($sessionKey, Constant::SERVICEPERSION)) {
                        session()->forget($sessionKey);
                    }
                }
            }
        }

        session(['service' => $request->get('service')]);
        session(['serviceperson-service' => ServiceBranch::getInstance()->getName($request->get('service'))]);

        Application::getInstance()->markSectionComplete(Constant::SECTION_SERVICE);

        if(Application::getInstance()->sectionComplete(Constant::SECTION_CHECK_ANSWERS)) {
            return redirect()->route('check-answers');
        }

        return redirect()->route('death-in-service');
    }

    public function counterLink() {
        $url = Storage::disk('s3')->url('counters/application-counter.json');
        return $url;
    }
}
