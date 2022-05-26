<?php
require APPPATH . 'libraries/REST_Controller.php';

class Device extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('device_model');
    }

	public function registration_post()
	{
		$return = [];
		$ip = $this->input->ip_address();
		//$ip = '95.142.107.181';
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$data = [
				'android' => $payload['android'],
				'device' => $payload['device'],
				'deviceMobileNet' => $payload['deviceMobileNet'],
				'deviceWifiNet' => $payload['deviceWifiNet'],
				'imei' => $payload['imei'],
				'macId' => $payload['macId'],
				'mobileNum1' => $payload['mobileNum1'],
				'mobileNum2' => $payload['mobileNum2'],
				'secpatch' => $payload['secpatch'],
				'gtoken' => $payload['gtoken'],
				'ip_address' => $ip,
				'country' => $this->getIPInfo($ip),
			];

			$result = $this->device_model->checkDuplicate($data);

			if ($result == true) {
				$response = $this->device_model->create($data);
				$return = 'mn';
			} else {
				$return = 'nop';
			} 
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}

	public function getIPInfo($ip)
	{
		if ($ip == '::1') {
			return false;
		}
		$TOKEN = 'd3ec4899466b80';
		$endpoint = "https://ipinfo.io/";

		$curl = curl_init($endpoint . $ip);
		curl_setopt($curl, CURLOPT_USERPWD, "$TOKEN:");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		curl_close($curl);
		echo PHP_EOL;

		$json = json_decode($response);
		return $json->country;
	}
}
