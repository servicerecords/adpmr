<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('feedback');
    }

    /**
     *
     */
    public function send(FeedbackRequest $request)
    {
        $notifyClient = new \Alphagov\Notifications\Client([
            'apiKey' => env('NOTIFY_API_KEY', 'srrdigitalproduction-8ae4b688-c5e2-45ff-a873-eb149b3e23ff-ed3db9dd-d928-4d4c-89dc-8d22b4265e75'),
            'httpClient' => new Client
        ]);

        try {
            $serviceEmail = explode('--', env('FEEDBACK_EMAIL', 'DBSCIO-ADPMRFeedback@mod.gov.uk'));

            $params = [
                'service' => $request->input('feedback-satisfaction'),
                'feedback' => (null !== $request->input('feedback-improvement') ? $request->input('feedback-improvement') : 'No feedback given')
            ];

            // @todo Make template ID for feedback come from an ENV var
            foreach ($serviceEmail as $email) {
                $notifyClient->sendEmail(
                    Str::replace('[@]', '@', $email),
                    env('FEEBBACK_TEMPLATE', '0f3b68c3-4589-4466-a743-73f73e841187'),
                    $params
                );
            }

            return redirect()->route('feedback.complete');
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function complete()
    {
        return view('feedback-complete');
    }
}
