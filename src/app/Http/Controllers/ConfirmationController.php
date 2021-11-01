<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Payment;
use BaoPham\DynamoDb\DynamoDbModel;
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
            return view('confirmation-error', [ 'payment' => $payment ]);
        }
    
        $this->updateCounter('paid');
        if(session('application-reference', false)) {
            session(['payment-status' => 'Paid']);
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
    
        $this->updateCounter('Exempt');
        if($application->isFree()) {
            session(['payment-status' => 'Exempt']);
            $application->getServiceperson();
            $application->notifyBranch();
            $application->notifyApplicant();

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
    
    /**
     * Update counter for applications
     * @param $paymentStatus
     */
    private function updateCounter( $paymentStatus ) {
        $counter = new Counter();
        $count = $counter->where('key', $paymentStatus)->first();
    
        if($count) {
            $count->update([
                'count' => $count->count + 1
            ]);
        } else {
            $counter->key = $paymentStatus;
            $counter->count = 1;
            $counter->save();
        }
    }

}
