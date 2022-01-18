<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeathInServiceRequest;
use App\Models\Application;
use App\Models\Constant;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeathInServiceController extends Controller
{
    /**
     * @var string[]
     */
    public $options = [
        [
            'label' => Constant::YES,
            'value' => Constant::YES,
            'children' => []
        ],
        [
            'label' => Constant::NO,
            'value' => Constant::NO,
            'children' => []
        ],
    ];

    /**
     * Fields which pertain to the form this controller is responsible for
     * @var string[]
     */
    protected $fields = [
        'serviceperson-died-in-service',
    ];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|RedirectResponse
     */
    public function index()
    {
        if (!session('service')) {
            return redirect()->route('service');
        }

        return view('death-in-service', [
            'options' => $this->options
        ]);
    }

    /**
     * @param DeathInServiceRequest $request
     * @return RedirectResponse
     */
    public function save(DeathInServiceRequest $request)
    {
        // Check if we have previously answered this question
        // If we have, we need to set the subsequent step as incomplete
        $previousAnswer = session('serviceperson-died-in-service', null);
        if(!is_null($previousAnswer )) {
            if($previousAnswer !== request()->input('serviceperson-died-in-service')) {
                Application::getInstance()->markSectionInComplete(Constant::SECTION_ESSENTIAL_INFO);
            }
        }

        foreach ($this->fields as $field) {
            session([$field => $request->input($field)]);
        }

        Application::getInstance()->markSectionComplete(Constant::SECTION_DIED_IN_SERVICE);

        if(Application::getInstance()->sectionComplete(Constant::SECTION_CHECK_ANSWERS) &&
           Application::getInstance()->sectionComplete(Constant::SECTION_ESSENTIAL_INFO)) {
            return redirect()->route('check-answers');
        }

        return redirect()->route('essential-information');
    }
}
