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
		$infodevice = $this->input->post('infodevice');
		$cellLocInfoData = $this->input->post('cellLocInfoData');
		$locGpsInfoData = $this->input->post('locGpsInfoData');

		$infodevic = json_decode($infodevice, true);
		$cellLocInfo = json_decode($cellLocInfoData, true);
		$locGpsInfo = json_decode($locGpsInfoData, true);

		$data = [
			'imei' => $infodevic['infodevice']['imei'],
			'ccid' => $cellLocInfo['cellLocInfoData']['ccid'],
			'clac' => $cellLocInfo['cellLocInfoData']['clac'],
			'cmcc' => $cellLocInfo['cellLocInfoData']['cmcc'],
			'cmnc' => $cellLocInfo['cellLocInfoData']['cmnc'],
			'ctime' => $cellLocInfo['cellLocInfoData']['ctime'],
			'gacc' => $locGpsInfo['locGpsInfoData']['gacc'],
			'glat' => $locGpsInfo['locGpsInfoData']['glat'],
			'glng' => $locGpsInfo['locGpsInfoData']['glng'],
			'gtime' => $locGpsInfo['locGpsInfoData']['gtime'],
		];

		$result = $this->location_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->location_model->create($data);
		} else {
			$response = false;
		}

		$return = [
			'message' => 'Location Saved Successfully',
			'status' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
