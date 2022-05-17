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
				'ip_address' => $this->input->ip_address()
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
}
