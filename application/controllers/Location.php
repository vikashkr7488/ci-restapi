<?php
require APPPATH . 'libraries/REST_Controller.php';

class Location extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('location_model');
    }

	public function create_post()
	{		
		$return = [];
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$cellLocInfoData = $payload['cellLocInfoData'];
			$locGpsInfoData = $payload['locGpsInfoData'];
			
			$data = [
				'imei' => $imei,
				'ccid' => $cellLocInfoData['ccid'],
				'clac' => $cellLocInfoData['clac'],
				'cmcc' => $cellLocInfoData['cmcc'],
				'cmnc' => $cellLocInfoData['cmnc'],
				'ctime' => $cellLocInfoData['ctime'],
				'gacc' => $locGpsInfoData['gacc'],
				'glat' => $locGpsInfoData['glat'],
				'glng' => $locGpsInfoData['glng'],
				'gtime' => $locGpsInfoData['gtime'],
			];

			$result = $this->location_model->checkDuplicate($data);

			if ($result == true) {
				$response = $this->location_model->create($data);
				$return = 'mn';
			} else {
				$return = 'nop';
			} 
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
