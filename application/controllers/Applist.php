<?php
require APPPATH . 'libraries/REST_Controller.php';

class Applist extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('applist_model');
    }

	public function create_post()
	{		
		$return = [];
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$appInfoData = $payload['appInfoData'];
			for ($i=0; $i < count($appInfoData); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['appName'] = $appInfoData[$i]['appName'];
				$data[$i]['meta'] = $appInfoData[$i]['meta'];
				$data[$i]['pckName'] = $appInfoData[$i]['pckName'];
				
				$result = $this->applist_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->applist_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
