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
		$infodevice = $this->input->post('infodevice');
		$appInfoData = $this->input->post('appInfoData');
		$infodevic = json_decode($infodevice, true);
		$appInfo = json_decode($appInfoData, true);

		if (isset($appInfoData)) {
			foreach ($appInfo as $value) {
				for ($i=0; $i < count($value); $i++) 
				{   
					$data[$i]['imei'] = $infodevic['infodevice']['imei'];
					$data[$i]['appName'] = $value[$i]['appName'];
					$data[$i]['meta'] = $value[$i]['meta'];
					$data[$i]['pckName'] = $value[$i]['pckName'];

					$result = $this->applist_model->checkDuplicate($data[$i]);

					if ($result == true) {
						$response = $this->applist_model->create($data[$i]);
					} else {
						$response = false;
					} 
				}
			}
		}

		$return = [
			'message' => 'Applist Saved Successfully',
			'status' => $response ?? false,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
