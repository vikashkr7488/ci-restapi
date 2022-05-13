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
		$data = [
			'imei' => $this->input->post('imei'),
			'callDuration' => $this->input->post('callDuration'),
			'callInfo' => $this->input->post('callInfo'),
			'numb' => $this->input->post('numb'),
		];

		$result = $this->calllog_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->calllog_model->create($data);
		} else {
			$response = $this->calllog_model->update($data);
		}

		$return = [
			'message' => 'Call Logs Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
