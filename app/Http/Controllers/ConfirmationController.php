<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationCounter;
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
        $payment = Payment::getInstance()->verifyPayment();
        $application = Application::getInstance();

        if ($payment !== true) {
            ApplicationCounter::getInstance()->increment(session('service'), Application::APPLICATION_FAILED);
            return redirect()->route('check-answers')->with('payment_failed', true);
        }

        if (session('application-reference', false)) {
            session(['payment-status' => Application::APPLICATION_PAID]);
            $application->getServiceperson();
            $application->notifyBranch();
            $application->notifyApplicant();

            ApplicationCounter::getInstance()->increment(session('service'), Application::APPLICATION_PAID);
            Application::getInstance()->cleanup();
            return redirect()->route('confirmation.complete');
        }

        Application::getInstance()->cleanup();
        return view('confirmation-error', ['payment' => $payment]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function free()
    {
        $application = Application::getInstance();

        if ($application->isFree()) {
            session(['payment-status' => Application::APPLICATION_EXEMPT]);
            $application->getServiceperson();
            $application->notifyBranch();
            $application->notifyApplicant();

            ApplicationCounter::getInstance()->increment(session('service'), Application::APPLICATION_EXEMPT);
            Application::getInstance()->cleanup();

            return redirect()->route('confirmation.complete');
        } else {
            return redirect()->route('cancel-application');
        }
    }

    /**
     * Show completed session page
     */
    public function complete()
    {
        return view('confirmation-success');
    }

}
