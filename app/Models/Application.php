<?php


namespace App\Models;


use Alphagov\Notifications\Client as Notify;
use Alphagov\Notifications\Exception\ApiException;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Application
{
    const APPLICATION_PAID = 'paid';
    const APPLICATION_EXEMPT = 'exempt';
    const APPLICATION_FAILED = 'failed';

    private static $instance = null;
    private $serviceperson = [];
    private $applicant = [];
    private $deathCertificate = false;

    private $standardQuestions = [
        ['label' => 'Service', 'field' => 'serviceperson-service', 'route' => 'service', 'change' => 'service branch'],
        ['label' => 'Rank', 'field' => 'serviceperson-service-rank', 'route' => 'serviceperson-details', 'change' => 'service rank'],
        ['label' => 'Service number', 'field' => 'serviceperson-service-number', 'route' => 'serviceperson-details', 'change' => 'service number'],
        ['label' => 'First name(s)', 'field' => 'serviceperson-first-name', 'route' => 'essential-information', 'change' => 'first name'],
        ['label' => 'Last name', 'field' => 'serviceperson-last-name', 'route' => 'essential-information', 'change' => 'last name'],
        ['label' => 'Place of birth', 'field' => 'serviceperson-place-of-birth', 'route' => 'essential-information', 'change' => 'place of birth'],
        ['label' => 'Date of birth', 'field' => 'serviceperson-date-of-birth-date', 'route' => 'essential-information', 'change' => 'date of birth'],
    ];

    private $questionOrder = [
        Constant::SERVICEPERSION => [
            ServiceBranch::NAVY => [
                ['label' => 'Date they joined', 'field' => 'serviceperson-enlisted-date', 'route' => 'serviceperson-details', 'change' => 'date they joined'],
                ['label' => 'Died in service', 'field' => 'serviceperson-died-in-service', 'route' => 'death-in-service', 'change' => 'if they died in service'],
                ['label' => 'Date of death in service', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'date they died in service'],
                ['label' => 'Further information', 'field' => 'serviceperson-discharged-information', 'route' => 'serviceperson-details', 'change' => 'further information'],
            ],
            ServiceBranch::ARMY => [
                ['label' => 'Died in service', 'field' => 'serviceperson-died-in-service', 'route' => 'death-in-service', 'change' => 'if they died in service'],
                ['label' => 'Year of death in service', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'year of death in service'],
                ['label' => 'Regt/Corps', 'field' => 'serviceperson-regiment', 'route' => 'serviceperson-details', 'change' => 'Regiment or Corps'],
                ['label' => 'Why they left the Army', 'field' => 'serviceperson-reason-for-leaving', 'route' => 'serviceperson-details', 'change' => 'why they left the Army'],
                ['label' => 'Territorial Army (TA)', 'field' => 'serviceperson-additional-service-ta', 'route' => 'serviceperson-details', 'change' => 'Territorial Army served'],
                ['label' => 'TA Number', 'field' => 'serviceperson-additional-service-ta-number', 'route' => 'serviceperson-details', 'change' => 'TA number'],
                ['label' => 'TA Regt/Corps', 'field' => 'serviceperson-additional-service-ta-regiment', 'route' => 'serviceperson-details', 'change' => 'TA Regiment or Corps'],
                ['label' => 'TA Dates', 'field' => 'serviceperson-additional-service-ta-dates', 'route' => 'serviceperson-details', 'change' => 'TA Dates'],
                ['label' => 'Army Emergency Reserve (AER)', 'field' => 'serviceperson-additional-service-aer', 'route' => 'serviceperson-details', 'change' => 'AER served'],
                ['label' => 'AER Reserve Number', 'field' => 'serviceperson-additional-service-aer-number', 'route' => 'serviceperson-details', 'change' => 'AER Reserve number'],
                ['label' => 'AER Regt/Corps', 'field' => 'serviceperson-additional-service-aer-regiment', 'route' => 'serviceperson-details', 'change' => 'AER Regiment or Corps'],
                ['label' => 'AER Dates', 'field' => 'serviceperson-additional-service-aer-dates', 'route' => 'serviceperson-details', 'change' => 'AER dates'],
                ['label' => 'Disability Pension been applied for', 'field' => 'serviceperson-disability-pension', 'route' => 'serviceperson-details', 'change' => 'if disability pension applied for'],
                ['label' => 'Further information', 'field' => 'serviceperson-additional-information', 'route' => 'serviceperson-details', 'change' => 'further information'],
            ],
            ServiceBranch::RAF => [
                ['label' => 'Date they joined', 'field' => 'serviceperson-enlisted-date', 'route' => 'serviceperson-details', 'change' => 'date they joined'],
                ['label' => 'Died in service', 'field' => 'serviceperson-died-in-service', 'route' => 'death-in-service', 'change' => 'if they died in service'],
                ['label' => 'Date of death in service', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'date of death in service'],
                ['label' => 'Further information', 'field' => 'serviceperson-discharged-information', 'route' => 'serviceperson-details', 'change' => 'further information'],
            ],
            ServiceBranch::HOME_GUARD => [
                ['label' => 'National Registration number', 'field' => 'serviceperson-service-number', 'route' => 'serviceperson-details', 'change' => 'national registration number'],
                ['label' => 'Date they joined', 'field' => 'serviceperson-enlisted-date', 'route' => 'serviceperson-details', 'change' => 'date they joined'],
                ['label' => 'Died in service', 'field' => 'serviceperson-died-in-service', 'route' => 'death-in-service', 'change' => 'if they died in service'],
                ['label' => 'Date of death in service', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'date of death in service'],
                ['label' => 'County they served in', 'field' => 'serviceperson-county-served', 'route' => 'serviceperson-details', 'change' => 'county they served in'],
                ['label' => 'Address when they joined', 'field' => 'serviceperson-address-when-joined', 'route' => 'serviceperson-details', 'change' => 'address when they joined'],
                ['label' => 'Numbers of any Battalions and Companies', 'field' => 'serviceperson-battalions', 'route' => 'serviceperson-details', 'change' => 'number of battalions and companies'],
            ],
        ],
        Constant::APPLICANT => [
            ['label' => 'Title', 'field' => 'applicant-title', 'route' => 'applicant-details', 'change' => 'your title'],
            ['label' => 'Your full name', 'field' => 'applicant-name', 'route' => 'applicant-details', 'change' => 'your full name'],
            ['label' => 'Email address', 'field' => 'applicant-email-address', 'route' => 'applicant-details', 'change' => 'your email address'],
            ['label' => 'Building and street', 'field' => 'applicant-address-line-1', 'route' => 'applicant-details', 'change' => 'your building and street'],
            ['label' => 'Address Line 2', 'field' => 'applicant-address-line-2', 'route' => 'applicant-details', 'change' => 'your second address line'],
            ['label' => 'Town', 'field' => 'applicant-address-town', 'route' => 'applicant-details', 'change' => 'your town'],
            ['label' => 'Postcode', 'field' => 'applicant-address-postcode', 'route' => 'applicant-details', 'change' => 'your postcode'],
            ['label' => 'Country', 'field' => 'applicant-address-country', 'route' => 'applicant-details', 'change' => 'your country'],
            ['label' => 'Telephone Number', 'field' => 'applicant-telephone', 'route' => 'applicant-details', 'change' => 'your telephone number'],
            ['label' => 'Relationship to serviceperson', 'field' => 'applicant-relationship', 'route' => 'applicant-relationship', 'change' => 'relationship to serviceperson'],
            ['label' => 'You were the spouse on death', 'field' => 'applicant-relationship-spouse-at-death', 'route' => 'applicant-relationship', 'change' => 'if you were their spouse at death'],
            ['label' => 'Parent at death (no spouse)', 'field' => 'applicant-relationship-no-surviving-spouse', 'route' => 'applicant-relationship', 'change' => 'if a parent confirmed no spouse'],
            ['label' => 'Immediate Next of kin', 'field' => 'applicant-next-of-kin', 'route' => 'applicant-next-of-kin', 'change' => 'if you are their next of kin'],
        ]
    ];

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $session = session()->all();
        foreach ($session as $sessionKey => $sessionValue) {
            if (Str::startsWith($sessionKey, 'serviceperson-')) {
                $this->serviceperson[$sessionKey] = $sessionValue;
                continue;
            }

            if (Str::startsWith($sessionKey, 'applicant-')) {
                $this->applicant[$sessionKey] = $sessionValue;
                continue;
            }

            if ($sessionKey === 'death-certificate') {
                $storage = Config::get('filesystems.disks.s3.bucket', false) ? 's3' : 'local';
                $this->deathCertificate = Storage::disk($storage)->exists(storage_path($sessionValue));
            }
        }

        if (session('serviceperson-died-in-service', Constant::YES) === Constant::NO) {
            switch (session('service', ServiceBranch::ARMY)) {
                case ServiceBranch::ARMY:
                    $this->questionOrder[Constant::SERVICEPERSION][ServiceBranch::ARMY][1] =
                        ['label' => 'Year of discharge', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'year of discharge'];
                    break;

                case ServiceBranch::NAVY:
                case ServiceBranch::RAF:
                    $this->questionOrder[Constant::SERVICEPERSION][session('service')][2] =
                        ['label' => 'Date they left', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'year of discharge'];
                    break;

                case ServiceBranch::HOME_GUARD:
                    $this->questionOrder[Constant::SERVICEPERSION][session('service')][3] =
                        ['label' => 'Date they left', 'field' => 'serviceperson-discharged-date', 'route' => 'serviceperson-details', 'change' => 'year of discharge'];
                    break;
            }
        }

//        switch (session('applicant-relationship')) {
//            case Constant::RELATION_PARENT:
//                $this->question[Constant::APPLICANT][session('service')]
//                break;
//
//            case Constant::RELATION_PARENT:
//                break;
//        }
    }

    /**
     * @return Application|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Application();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public function getServiceperson()
    {
        $responses = array_merge(
            $this->standardQuestions,
            $this->questionOrder[Constant::SERVICEPERSION][session('service', ServiceBranch::ARMY)]
        );

        foreach ($responses as $responseKey => $response) {
            if (Str::endsWith($response['field'], '-date')) {
                $responses[$responseKey]['value'] = $this->generateDateString($response['field']);
            } else {
                $responses[$responseKey]['value'] = session($response['field'], '');
            }

            if (session('serviceperson-died-in-service', Constant::YES) == 'Yes') {
                if (session('service') == ServiceBranch::HOME_GUARD) {
                    session(['label-serviceperson-discharged' => 'Date of death in service']);
                } else {
                    session(['label-serviceperson-discharged' => 'Service end date']);
                }
            } else {
                session(['label-serviceperson-discharged' => 'Date they left']);
            }
        }

        return $responses;
    }

    /**
     * @return array
     */
    public function getApplicant()
    {
        $responses = $this->questionOrder[Constant::APPLICANT];

        foreach ($responses as $responseKey => $response) {
            if (session($response['field'], '') == trim('')) {
                unset($responses[$responseKey]);
                continue;
            }

            $responses[$responseKey]['value'] = session($response['field'], '');

            switch (session('service', ServiceBranch::HOME_GUARD)) {
                case ServiceBranch::HOME_GUARD:
                    if ($response['label'] === 'Service number') {
                        $responses[$responseKey]['label'] = 'National Registration';
                    }
                    break;
            }

            if ($responses[$responseKey]['field'] === 'applicant-relationship') {
                switch (session('applicant-relationship', Constant::RELATION_OTHER)) {
                    case Constant::RELATION_OTHER:
                        $responses[$responseKey]['value'] = session('applicant-relationship-other', '');
                        break;

//                    case Constant::RELATION_PARENT:
//                        if ($this->getResponse('applicant-relationship-no-surviving-spouse') === Constant::YES) {
//                            $responses[$responseKey]['value'] = 'Parent at death (no spouse)';
//                        }
//                        break;
                }
            }
        }

        return $responses;
    }

    /**
     * @return array
     */
    public function getServicepersionAnswers()
    {
        return $this->questionOrder;
    }

    /**
     * @return bool|string
     */
    public function getDeathCertificate()
    {
        return $this->deathCertificate;
    }

    /**
     * @return bool
     */
    public function isFree()
    {
        switch (session('applicant-relationship')) {
            case Constant::RELATION_SPOUSE:
                return true;
            case Constant::RELATION_PARENT:
                return session('applicant-relationship-no-surviving-spouse', true);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function deathCertificateRequired()
    {
        $diedInService = session('serviceperson-died-in-service', Constant::YES);
        $ageToDate = date('Y') - session('serviceperson-date-of-birth-date-year', date('Y'));

        if ($diedInService === Constant::YES)
            return false;

        if ($ageToDate >= 116)
            return false;

        return true;
    }

    /**
     * @param $section
     * @return int
     */
    public function sectionComplete($section)
    {
        return (session('section-complete', 0) & $section);
    }

    /**
     * @param $section
     */
    public function markSectionInComplete($section)
    {
        return (session('section-complete', 0) & ~$section);
    }

    /**
     * @return void
     */
    public function resetCompletedSections()
    {
        session(['section-complete' => 0]);
    }

    /**
     * @param $section
     */
    public function markSectionComplete($section)
    {
        session(['section-complete' => session('section-complete', 0) | $section]);
    }

    /**
     * Send notification to DBS Branch
     */
    public function notifyBranch()
    {
        $serviceEmail = explode('--', ServiceBranch::getInstance()->getEmailAddress(session('service')));
        $serviceBranch = ServiceBranch::getInstance();
        $templateId = $serviceBranch->getEmailTemplateId(session('service'));
        $notify = $this->getClient();
        $template = $notify->getTemplate($templateId);
        $data = [];

        if ($template) {
            $properties = $template['personalisation'];

            foreach ($properties as $property => $propertyValue) {
                if (session()->has($property)) {
                    $data[$property] = session($property, 'Field left blank');
                } else {
                    $data[$property] = 'Field left blank';

                    if (Str::endsWith($property, '-date')) {
                        $data[$property] = $this->generateDateString($property);

                        if (!$data[$property]) {
                            $data[$property] = 'Field left blank';
                        }
                    }
                }
            }
        }

        foreach ($serviceEmail as $email) {
            $notify->sendEmail(
                Str::replace('[@]', '@', $email),
                $templateId,
                $data,
                session('applicant-reference')
            );
        }
    }

    /**
     * Send notification to applicant
     */
    public function notifyApplicant()
    {
        $templateId = '567f3c9f-4e9f-45b1-99ef-1d559c0f676d';
        $data = [
            'service_feedback_url' => env('APP_URL', 'http://srrdigital-sandbox.cloudapps.digital/feedback'),
            'dbs_branch' => $dbs_office = ServiceBranch::getInstance()->getServiceBranch(session('service')) ?? '',
            'dbs_email' => ServiceBranch::getInstance()->getEmailAddress(session('service')) ?? '',
            'reference_number' => session('application-reference') ?? '',
        ];

        try {
            return $this->getClient()->sendEmail(
                session('applicant-email-address'),
                $templateId,
                $data);
        } catch (ApiException $e) {
            Log::critical($e->getErrorMessage());
            $failure = [
                'email' => session('applicant-email-address'),
                'template' => $templateId,
                'data' => $data,
            ];

            $failureFile = storage_path('app/notify/failure.json');
            $failures = json_decode(file_get_contents($failureFile));
            array_push($failures, $failure);
            file_put_contents($failureFile, json_encode($failures));

            return $e;
        }
    }

    /**
     * Create a unique reference for each new request.
     */
    public function createReference()
    {
        $code = ServiceBranch::getInstance()->getCode(session('service', ServiceBranch::ARMY));
        $reference = $code . '-' . time();

        session(['application-reference' => $reference]);

        return $reference;
    }

    /**
     * @param $field
     * @return string
     */
    protected function generateDateString($field)
    {
        $day = $month = $year = '';

        if ($field === 'serviceperson-date-of-birth-date') {
            $day = session('serviceperson-date-of-birth-date-day', Constant::DAY_ZERO_PLACEHOLDER);
            $month = session('serviceperson-date-of-birth-date-month', Constant::MONTH_ZERO_PLACEHOLDER);
            $year = session('serviceperson-date-of-birth-date-year', Constant::YEAR_ZERO_PLACEHOLDER);
        } else {
            $fields = ServiceBranch::getInstance()->getFields(
                session('service', ServiceBranch::ARMY),
                session('servicepersion-died-in-service')
            );

            if (array_key_exists($field . '-day', array_flip($fields))) {
                $day = session($field . '-day', Constant::DAY_ZERO_PLACEHOLDER);
            }

            if (array_key_exists($field . '-month', array_flip($fields))) {
                $month = session($field . '-month', Constant::MONTH_ZERO_PLACEHOLDER);
            }

            if (array_key_exists($field . '-year', array_flip($fields))) {
                $year = session($field . '-year', Constant::YEAR_ZERO_PLACEHOLDER);
            }
        }

        if (trim($day) == '') $day = Constant::DAY_ZERO_PLACEHOLDER;
        else $day = sprintf('%02d', $day);

        if (trim($month) == '') $month = Constant::MONTH_ZERO_PLACEHOLDER;
        else $month = sprintf('%02d', $month);

        if (trim($year) == '') $year = Constant::YEAR_ZERO_PLACEHOLDER;
        else $year = sprintf('%04d', $year);

        return $this->formatDateResponse($year . '-' . $month . '-' . $day);
    }

    /**
     * @param $response
     * @return string
     */
    public function formatDateResponse($response)
    {
        list($year, $month, $day) = explode('-', $response);

        $dateResponse = [];
        if ($year == '0000' || $month == '00' || $day == '00') {
            if ($day !== '00') array_push($dateResponse, $day);

            array_push($dateResponse,
                ($month !== '00') ? (DateTime::createFromFormat('!m', $month))->format('F')
                    : (($day !== '00') ? 'Unknown month' : false));

            if ($year !== '0000') array_push($dateResponse, $year);
        } else {
            $dateResponse = explode('-', \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $response)->format('d F Y'));
        }

        return (sizeof($dateResponse) > 0) ? trim(join(' ', $dateResponse)) : 'Not answered';
    }

    /**
     * @return Notify
     */
    public function getClient()
    {
        return new Notify([
            'apiKey' => env('NOTIFY_API_KEY', 'srrdigitalproduction-8ae4b688-c5e2-45ff-a873-eb149b3e23ff-ed3db9dd-d928-4d4c-89dc-8d22b4265e75'),
            'httpClient' => new Client()
        ]);
    }

    /**
     * Clear up the session
     */
    public function cleanup()
    {
        $reference = session('application-reference');

        if (session('death-certificate')) {
            Storage::delete(session('death-certificate'));
        }

        session()->flush();
        session(['application-reference' => $reference]);
    }

    /**
     *
     */
    public function countApplication($type = null, $branch = ServiceBranch::RAF)
    {
        if (!in_array($type, [
            self::APPLICATION_PAID,
            self::APPLICATION_EXEMPT,
            self::APPLICATION_FAILED
        ])) return;

        $counterKey = Carbon::now()->startOfMonth()->toDateString() . '::' .
            Carbon::now()->endOfMonth()->toDateString();
        $s3Bucket = Config::get('filesystems.disks.s3.bucket', false);
        $counterFile = Constant::COUNTER_FILE;
        $counterData = new \stdClass();

        if (!Storage::disk('local')->exists($counterFile)) {
            if ($s3Bucket && Storage::disk('s3')->exists($counterFile)) {
                $counterData = (object)json_decode(Storage::disk('s3')->get($counterFile));
            }
        } else {
            $counterData = (object)json_decode(Storage::disk('local')->get($counterFile));
        }

        $totalPlaceholder = (object)[
            self::APPLICATION_PAID => 0,
            self::APPLICATION_EXEMPT => 0,
            self::APPLICATION_FAILED => 0,
        ];

        $placeholderData = (object)[
            self::APPLICATION_PAID => 0,
            self::APPLICATION_EXEMPT => 0,
            self::APPLICATION_FAILED => 0,
            'total' => clone $totalPlaceholder,
            'last_update' => date('Y-m-d H:i:s')
        ];

        if (!isset($counterData->$counterKey)) {
            $counterData->$counterKey = (object)[
                ServiceBranch::RAF => clone $placeholderData,
                ServiceBranch::NAVY => clone $placeholderData,
                ServiceBranch::ARMY => clone $placeholderData,
                ServiceBranch::HOME_GUARD => clone $placeholderData,
            ];
        } elseif (!isset($counterData->$counterKey->$branch)) {
            $counterData->$counterKey->$branch = clone $placeholderData;
        }

        if (!isset($counterData->total)) {
            $counterData->total = clone $totalPlaceholder;

            foreach ($counterData as $counters) {
                $counterData->total->paid += $counters->paid ?? 0;
                $counterData->total->paid += $counters->{ServiceBranch::RAF}->paid ?? 0;
                $counterData->total->paid += $counters->{ServiceBranch::NAVY}->paid ?? 0;
                $counterData->total->paid += $counters->{ServiceBranch::ARMY}->paid ?? 0;
                $counterData->total->paid += $counters->{ServiceBranch::HOME_GUARD}->paid ?? 0;

                $counterData->total->exempt += $counters->exempt ?? 0;
                $counterData->total->exempt += $counters->{ServiceBranch::RAF}->exempt ?? 0;
                $counterData->total->exempt += $counters->{ServiceBranch::NAVY}->exempt ?? 0;
                $counterData->total->exempt += $counters->{ServiceBranch::ARMY}->exempt ?? 0;
                $counterData->total->exempt += $counters->{ServiceBranch::HOME_GUARD}->exempt ?? 0;

                $counterData->total->failed += $counters->failed ?? 0;
                $counterData->total->failed += $counters->{ServiceBranch::RAF}->failed ?? 0;
                $counterData->total->failed += $counters->{ServiceBranch::NAVY}->failed ?? 0;
                $counterData->total->failed += $counters->{ServiceBranch::ARMY}->failed ?? 0;
                $counterData->total->failed += $counters->{ServiceBranch::HOME_GUARD}->failed ?? 0;
            }
        }

        if(!isset($counterData->total->$branch)) {
            $counterData->total->$branch = clone $totalPlaceholder;
            foreach ($counterData as $counters) {
                $counterData->total->$branch->paid += (isset($counters->$branch)) ? $counters->$branch->paid ?? 0 : 0;
                $counterData->total->$branch->exempt += (isset($counters->$branch)) ? $counters->$branch->exempt ?? 0 : 0;
                $counterData->total->$branch->failed += (isset($counters->$branch)) ? $counters->$branch->failed ?? 0 : 0;
            }
        }

        if (!isset($counterData->$counterKey->total)) {
            $counterData->$counterKey->total = clone $totalPlaceholder;

            $counterData->$counterKey->total->paid += $counters->$counterKey->paid ?? 0;
            $counterData->$counterKey->total->paid += $counters->$counterKey->{ServiceBranch::RAF}->paid ?? 0;
            $counterData->$counterKey->total->paid += $counters->$counterKey->{ServiceBranch::NAVY}->paid ?? 0;
            $counterData->$counterKey->total->paid += $counters->$counterKey->{ServiceBranch::ARMY}->paid ?? 0;
            $counterData->$counterKey->total->paid += $counters->$counterKey->{ServiceBranch::HOME_GUARD}->paid ?? 0;
            $counterData->$counterKey->total->exempt += $counters->$counterKey->exempt ?? 0;
            $counterData->$counterKey->total->exempt += $counters->$counterKey->{ServiceBranch::RAF}->exempt ?? 0;
            $counterData->$counterKey->total->exempt += $counters->$counterKey->{ServiceBranch::NAVY}->exempt ?? 0;
            $counterData->$counterKey->total->exempt += $counters->$counterKey->{ServiceBranch::ARMY}->exempt ?? 0;
            $counterData->$counterKey->total->exempt += $counters->$counterKey->{ServiceBranch::HOME_GUARD}->exempt ?? 0;
            $counterData->$counterKey->total->failed += $counters->$counterKey->failed ?? 0;
            $counterData->$counterKey->total->failed += $counters->$counterKey->{ServiceBranch::RAF}->failed ?? 0;
            $counterData->$counterKey->total->failed += $counters->$counterKey->{ServiceBranch::NAVY}->failed ?? 0;
            $counterData->$counterKey->total->failed += $counters->$counterKey->{ServiceBranch::ARMY}->failed ?? 0;
            $counterData->$counterKey->total->failed += $counters->$counterKey->{ServiceBranch::HOME_GUARD}->failed ?? 0;
        }

        if (!isset($counterData->$counterKey->$branch->total)) {
            $counterData->$counterKey->$branch->total = clone $totalPlaceholder;

            $counterData->$counterKey->$branch->total->paid +=   $counters->$counterKey->$branch->paid ?? 0;
            $counterData->$counterKey->$branch->total->exempt += $counters->$counterKey->$branch->exempt ?? 0;
            $counterData->$counterKey->$branch->total->failed += $counters->$counterKey->$branch->failed ?? 0;
        }

        $counterData->$counterKey->$branch->$type++;
        $counterData->$counterKey->$branch->last_update = date('Y-m-d H:i:s');

        // Total up all values
        $counterData->total->$type++;
        $counterData->total->$branch->$type++;
        $counterData->$counterKey->$branch->total->$type++;

        Storage::disk('local')->put($counterFile, json_encode($counterData, JSON_PRETTY_PRINT));
        // Storage::disk('local')->delete($counterFile);
        if ($s3Bucket) {
            Storage::disk('s3')->put($counterFile, json_encode($counterData, JSON_PRETTY_PRINT));
        }


    }

    /**
     * @param $questions
     * @param $field
     * @return void
     */
    private function getResponse($field)
    {
        foreach (session()->all() as $key => $value) {
            if ($key === $field) {
                return $value;
            }
        }

        return null;
    }
}
