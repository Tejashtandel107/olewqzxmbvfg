<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\LineIncomeMonthly;
use App\Facades\Helper;
#use Illuminate\Support\Facades\Log;

class DevPOSApiService
{
	protected $apiBaseUrl;
	protected $apiTokenUrl;
	protected $apiToken = null;
	protected $basicAuth;
	protected $settings;
	protected $bank_info;

	public function __construct()
	{
		$this->apiBaseUrl = config('services.devpos.api_url');
		$this->apiTokenUrl = config('services.devpos.api_token_url');
		$this->basicAuth = config('services.devpos.basic_auth');
        $this->bank_info = config('constant.BANK_INFO');
		$this->settings = Helper::getSettings();
		$this->setToken();
	}

	/**
	 * Perform a GET request.
	 *
	 * @param string $endpoint
	 * @param array $queryParams
	 * @return array
	 */
	protected function get(string $endpoint, array $queryParams = []): array
	{
		$response = Http::withToken($this->apiToken)->asForm()->get("{$this->apiBaseUrl}/{$endpoint}", $queryParams);
		return $this->parseAPIResponse($response);
	}

	/**
	 * Perform a POST request.
	 *
	 * @param string $endpoint
	 * @param array $data
	 * @return array
	 */
	protected function post(string $endpoint, array $data = []): array
	{
		$response = Http::withToken($this->apiToken)->post("{$this->apiBaseUrl}/{$endpoint}",$data);
		return $this->parseAPIResponse($response);
	}

	public function setToken(): void
	{
		$this->apiToken = null;
		$options = [
			'username' => $this->settings['devpos_username'],
			'password' => $this->settings['devpos_password'],
			'grant_type' => 'password',
		];
		$response = Http::withHeaders($this->getTokenHeaders())->asForm()->post($this->apiTokenUrl, $options);
		if ($response->successful()) {
			$this->apiToken = $response->object()->access_token;
		}
	}

	/**
	 * Get the default headers for the API request.
	 *
	 * @return array
	 */
	private function getHeaders(): array
	{
		return [
			'Authorization' => 'Bearer ' . $this->apiToken,
		];
	}
	/**
	 * Get the default headers for the Token API request.
	 *
	 * @return array
	 */

	private function getTokenHeaders(): array
	{
		return [
			'Authorization' => 'Basic ' . $this->basicAuth,
			'tenant' => $this->settings['devpos_tenant'],
		];
	}

	/**
     * Send a POST request to create an invoice.
     *
     * @param array $invoiceData
     * @return array
     */
    public function createInvoice(LineIncomeMonthly $lineIncomeMonthly)
    {
		$totalAmount = $lineIncomeMonthly->total_amount;
		
		$client = $lineIncomeMonthly->user()->with('profile')->first();
		$exchangeRate = Cache::get('exchange_rate') ?? 0;		
        $invoiceData = [
            "invoiceType" => 1,
            "businessUnitCode" => $this->settings['devpos_business_unit_code'],
            "operatorCode" => $this->settings['devpos_operator_code'],
            "currencyCode" => "EUR",
            "exchangeRate" => $exchangeRate
        ];

        $invoiceData["customer"] = [
            "idNumber" => $lineIncomeMonthly->user->profile->vat_number,
            "idType" => 4,
            "name" => $client->name
        ];
        $invoiceData["invoiceProducts"][] = [
            "name" => "Outsourcing Contabilità Monthly Service",
            "barcode" => 3,
            "unitPrice" => $this->getAmountInLek($totalAmount),
            "quantity" => 1,
            "unit" => "M4",
			"vatRate" => 0,
        ];
        $invoiceData["invoicePayments"][]=[
            "paymentMethodType" => 6,
            "amount" => $this->getAmountInLek($totalAmount),
			"accountDetails" => [
				"accountNumber" => $this->bank_info[0]['account_number'],
				"bankName" => $this->bank_info[0]['name'],
				"bankCity" => $this->bank_info[0]['city'],
				"bankAddress" => $this->bank_info[0]['address'],
				"bankSwift" => $this->bank_info[0]['swift_code']
			]
		];
		return $this->post('Invoice', $invoiceData);
    }

	private function getAmountInLek($amount=0){
		$exchangeRate = Cache::get('exchange_rate') ?? 1;
		return number_format(($amount * $exchangeRate),2,'.','');
	}

	public function parseAPIResponse(Response $response){
		$errors=[];

		if($response->successful()){
			return $response->json();
		}
		elseif($response->badRequest()){
			$message="Bad Request";
			if($response->json()["message"]){
				$errors[] = $response->json()["message"];
			}
		}
		elseif($response->forbidden()){
			$message = "Forbidden access.";
			$error[] = "Accesso negato. Non hai l'autorizzazione per eseguire questa azione devPOS.";
		}
		elseif($response->unauthorized()){
			$message = "Unauthorized access.";
			$errors[] = "Credenziali DevPOS non valide.";	
		}
		elseif($response->requestTimeout()){
			$message = "Request timeout.";
			$errors[] = "Timeout della richiesta API devPOS. Riprova più tardi.";	
		}
		elseif($response->unprocessableEntity()){
			$result = $response->json();
			$message = ($result["message"]) ?? "Validation failed";
			foreach ($result["errors"] as $error) {
                foreach($error["message"] as $value){
                    $errors[] = implode(": ",array($error["field"],$value)); 
                }
            }			
		}
		else{
			$message = "Server error.";
		}
		return [
			"error"=>true,
			"message"=>$message,
			"errors"=>$errors
		];
	}
}
