<?php
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('account_model');
    }

	public function create_post()
	{		
		$return = [];
		$data = [
			'imei' => $this->input->post('imei'),
			'acc_name' => $this->input->post('acc_name'),
			'acc_type' => $this->input->post('acc_type'),
		];

		$result = $this->account_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->account_model->create($data);
		} else {
			$response = $this->account_model->update($data);
		}

		$return = [
			'message' => 'Account Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
