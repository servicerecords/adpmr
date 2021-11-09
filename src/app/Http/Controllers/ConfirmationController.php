<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    /**
     * @param Request $request
     * @param $uuid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function paid(Request $request, $uuid)
    {
        $payment  = Payment::getInstance()->verifyPayment();
        $application = Application::getInstance();

        if($payment !== true) {
            Application::getInstance()->cleanup();
    
            $application->countApplication(Application::APPLICATION_FAILED);
            return view('confirmation-error', [ 'payment' => $payment ]);
        }
    
        if(session('application-reference', false)) {
    
            $application->countApplication(Application::APPLICATION_PAID);
            session(['payment-status' => Application::APPLICATION_PAID]);
            $application->getServiceperson();
            $application->notifyBranch();
            $application->notifyApplicant();

            Application::getInstance()->cleanup();
            return redirect()->route('confirmation.complete');
        }

        Application::getInstance()->cleanup();
        return view('confirmation-error', [ 'payment' => $payment ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function free() {
        $application = Application::getInstance();
    
        if($application->isFree()) {
            session(['payment-status' => Application::APPLICATION_EXEMPT]);
            $application->getServiceperson();
            $application->notifyBranch();
            $application->notifyApplicant();

            $application->countApplication(Application::APPLICATION_EXEMPT);
            Application::getInstance()->cleanup();
            
            return redirect()->route('confirmation.complete');
        } else {
            return redirect()->route('cancel-application');
        }
    }

    /**
     * Show completed session page
     */
    public function complete() {
        return view('confirmation-success');
    }

}
