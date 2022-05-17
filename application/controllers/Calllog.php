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
		$infodevice = $this->input->post('infodevice');
		$callsData = $this->input->post('callsData');
		$infodevic = json_decode($infodevice, true);
		$calls = json_decode($callsData, true);

		if (isset($calls)) {
			foreach ($calls as $value) {
				for ($i=0; $i < count($value); $i++) 
				{   
					$data[$i]['imei'] = $infodevic['infodevice']['imei'];
					$data[$i]['callDuration'] = $value[$i]['callDuration'];
					$data[$i]['callInfo'] = $value[$i]['callInfo'];
					$data[$i]['numb'] = $value[$i]['numb'];

					$result = $this->calllog_model->checkDuplicate($data[$i]);

					if ($result == true) {
						$response = $this->calllog_model->create($data[$i]);
					} else {
						$response = false;
					} 
				}
			}
		}

		$return = [
			'message' => 'Call Logs Saved Successfully',
			'status' => $response ?? false,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
