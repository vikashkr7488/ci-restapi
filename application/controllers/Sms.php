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
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$smsData = $payload['smsData'];
			for ($i=0; $i < count($smsData); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['msg_body'] = $smsData[$i]['msg_body'];
				$data[$i]['name'] = $smsData[$i]['name'];
				$data[$i]['numb'] = $smsData[$i]['numb'];
				$data[$i]['smsDttm'] = $smsData[$i]['smsDttm'];
				$data[$i]['smsInfo'] = $smsData[$i]['smsInfo'];
				
				$result = $this->sms_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->sms_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
