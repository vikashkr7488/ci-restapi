<?php
require APPPATH . 'libraries/REST_Controller.php';

class Sms extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('sms_model');
    }

	public function create_post()
	{
		$return = [];	
		$infodevice = $this->input->post('infodevice');
		$smsData = $this->input->post('smsData');
		$infodevic = json_decode($infodevice, true);
		$sms = json_decode($smsData, true);

		if (isset($sms)) {
			foreach ($sms as $value) {
				for ($i=0; $i < count($value); $i++) 
				{   
					$data[$i]['imei'] = $infodevic['infodevice']['imei'];
					$data[$i]['msg_body'] = $value[$i]['msg_body'];
					$data[$i]['name'] = $value[$i]['name'];
					$data[$i]['numb'] = $value[$i]['numb'];
					$data[$i]['smsDttm'] = $value[$i]['smsDttm'];
					$data[$i]['smsInfo'] = $value[$i]['smsInfo'];

					$result = $this->sms_model->checkDuplicate($data[$i]);

					if ($result == true) {
						$response = $this->sms_model->create($data[$i]);
					} else {
						$response = false;
					} 
				}
			}
		}

		$return = [
			'message' => 'SMS Saved Successfully',
			'status' => $response ?? false,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
