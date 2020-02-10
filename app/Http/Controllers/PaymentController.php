<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
	protected $cost;
	protected $description = "Service Record Request";
	protected $return_url;

	public function __construct()
	{
		$this->cost = env('REQUEST_PRICE');
	}

	public function payment(Request $request)
	{
		return view('payment', ['paying' => true]);
	}

    public function processPayment(Request $request)
	{

		$post_params = [
			'amount' => env('REQUEST_PRICE'),
			'reference' => $request->session()->get('reference'),
			'description' => $this->description,
			'return_url' => env('GOV_PAY_RETURN_URL') . '/confirmation',
            'email' => $request->session()->get('your_details.email')
        ];

		if($request->session()->get('your_details.use_billing') == 'Yes') {
            $post_params['prefilled_cardholder_details'] = [
                "cardholder_name" => '',
                "billing_address" => [
                    'line1' => null !== $request->session()->get('your_details.address_line_1') ? $request->session()->get('your_details.address_line_1') : '',
                    'line2' => null !== $request->session()->get('your_details.address_line_2') ? $request->session()->get('your_details.address_line_2') : '',
                    'postcode' => null !== $request->session()->get('your_details.address_postcode') ? $request->session()->get('your_details.address_postcode') : '',
                    'city' => null !== $request->session()->get('your_details.address_town') ? $request->session()->get('your_details.address_town') : '',
                    'country' => null !== $request->session()->get('your_details.country') ? $request->session()->get('your_details.country') : ''
                ]
            ];
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://publicapi.payments.service.gov.uk/v1/payments",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($post_params, JSON_UNESCAPED_SLASHES),
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"Authorization: Bearer " . env('GOV_PAY_API_KEY'),
				"Content-Type: application/json",
			),
		));


		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response, true);
			return redirect($response['_links']['next_url']['href']);
		}
	}
}
