<?php
require APPPATH . 'libraries/REST_Controller.php';

class Calllog extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('calllog_model');
    }

	public function create_post()
	{
		$return = [];
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$callsData = $payload['callsData'];
			for ($i=0; $i < count($callsData); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['callDuration'] = $callsData[$i]['callDuration'];
				$data[$i]['callInfo'] = $callsData[$i]['callInfo'];
				$data[$i]['numb'] = $callsData[$i]['numb'];
				
				$result = $this->calllog_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->calllog_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
