<?php
require APPPATH . 'libraries/REST_Controller.php';

class Contact extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('contact_model');
    }

	public function create_post()
	{
		$return = [];
		$data = [
			'imei' => $this->input->post('imei'),
			'name' => $this->input->post('name'),
			'numb' => $this->input->post('numb')
		];

		$result = $this->contact_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->contact_model->create($data);
		} else {
			$response = $this->contact_model->update($data);
		}

		$return = [
			'message' => 'Contact Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
