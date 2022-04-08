<?php

namespace App\Http\Requests;

use App\Models\Constant;
use App\Models\ServiceBranch;
use App\Rules\Day;
use App\Rules\Month;
use Carbon\Carbon;
use Illuminate\Validation\Factory as ValidationFactory;

class ServicepersonDetailsRequest extends DigitalRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        switch (session('service', ServiceBranch::ARMY)) {
            case ServiceBranch::ARMY:
                $rules = [
                    'serviceperson-discharged-date-year' => 'nullable|integer|max:' . date('Y'),
                ];
                break;

            case ServiceBranch::NAVY:
                $rules = [
                    'serviceperson-enlisted-date-day' => [
                        'nullable',
                        new Day(
                            request()->input('serviceperson-enlisted-date-month'),
                            request()->input('serviceperson-enlisted-date-year'),
                            'Enter a valid day they joined'
                        )],
                    'serviceperson-enlisted-date-month' => ['nullable',  new Month('Enter a valid month they joined')],
                    'serviceperson-enlisted-date-year'  => ['nullable',
                        'integer',
                        'max:' . date('Y'),
                        'min:1926'
                    ],
                    'serviceperson-discharged-date-day' => ['nullable',
                        new Day(
                            request()->input('serviceperson-discharged-date-month'),
                            request()->input('serviceperson-discharged-date-year'),
                            session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                                'Enter a valid day they died in service' :
                                'Enter a valid day they left service'
                        )],
                    'serviceperson-discharged-date-month' => ['nullable', new Month(
                        session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                            'Enter a valid month they died in service' :
                            'Enter a valid month they left service'
                    )],
                    'serviceperson-discharged-date-year' => [
                        'nullable',
                        'integer',
                        'max:' . date('Y'),
                    ]
                ];
                break;

            case ServiceBranch::RAF:
                $rules = [
                    'serviceperson-enlisted-date-day' => [
                        'nullable',
                        new Day(
                            request()->input('serviceperson-enlisted-date-month'),
                            request()->input('serviceperson-enlisted-date-year'),
                            'Enter a valid day they joined'
                        )],
                    'serviceperson-enlisted-date-month' => ['nullable',  new Month('Enter a valid month they joined')],
                    'serviceperson-enlisted-date-year'  => 'nullable|integer|max:' . date('Y'),
                    'serviceperson-discharged-date-day' => ['nullable',
                        new Day(
                            request()->input('serviceperson-discharged-date-month'),
                            request()->input('serviceperson-discharged-date-year'),
                            session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                                'Enter a valid day they died in service' :
                                'Enter a valid day they left service'
                        )],
                    'serviceperson-discharged-date-month' => ['nullable', new Month(
                        session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                            'Enter a valid month they died in service' :
                            'Enter a valid month they left service'
                    )],
                    'serviceperson-discharged-date-year' => [
                        'nullable',
                        'integer',
                        'max:' . date('Y'),
                        'min:1920',
                    ]
                ];
                break;
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        $messages = [];

        switch (session('service', ServiceBranch::ARMY)) {
            case ServiceBranch::NAVY:
                $messages = [
                    'serviceperson-discharged-date-year.integer' => 'Enter a valid year',
                    'serviceperson-enlisted-date-year.integer' => 'Enter a valid year',
                    'serviceperson-enlisted-date-year.max' => 'Year joined service must be in the past',
                    'serviceperson-discharged-date-year.max' =>
                        session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                            'Year of death in service must be in the past' :
                            'Year they must be in the past',
                    'serviceperson-enlisted-date-year.min' => 'Service record not held',
                ];
                break;

            case ServiceBranch::RAF:
                $messages = [
                    'serviceperson-discharged-date-year.integer' => 'Enter a valid year',
                    'serviceperson-enlisted-date-year.integer' => 'Enter a valid year',
                    'serviceperson-enlisted-date-year.max' => 'Year joined service must be in the past',
                    'serviceperson-discharged-date-year.max' =>
                        session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                            'Year of death in service must be in the past' :
                            'Year they must be in the past',

                    'serviceperson-discharged-date-year.min' => 'Date of death is before 1 Jan 1920',
                ];
                break;

            case ServiceBranch::ARMY:
                $messages = [
                    'serviceperson-discharged-date-year.integer' => 'Enter a valid year',
                    'serviceperson-discharged-date-year.max' =>
                        session('serviceperson-died-in-service', Constant::YES) === Constant::YES ?
                            'Year of death must be in the past' :
                            'Year of discharge must be in the past',
                ];
                break;
        }
        return $messages;
    }
}
