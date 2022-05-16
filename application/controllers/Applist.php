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
		$data = [
			'imei' => $this->input->post('imei'),
			'appName' => $this->input->post('appName'),
			'meta' => $this->input->post('meta'),
			'pckName' => $this->input->post('pckName'),
		];

		$result = $this->applist_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->applist_model->create($data);
		} else {
			$response = $this->applist_model->update($data);
		}

		$return = [
			'message' => 'Applist Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
