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
		$data = [
			'imei' => $this->input->post('imei'),
			'ccid' => $this->input->post('ccid'),
			'clac' => $this->input->post('clac'),
			'cmcc' => $this->input->post('cmcc'),
			'cmnc' => $this->input->post('cmnc'),
			'ctime' => $this->input->post('ctime'),
			'gacc' => $this->input->post('gacc'),
			'glat' => $this->input->post('glat'),
			'glng' => $this->input->post('glng'),
			'gtime' => $this->input->post('gtime'),
		];

		$result = $this->location_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->location_model->create($data);
		} else {
			$response = $this->location_model->update($data);
		}

		$return = [
			'message' => 'Location Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
