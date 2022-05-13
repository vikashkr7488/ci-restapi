<?php
require APPPATH . 'libraries/REST_Controller.php';

class Sms extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('sms_model');
    }

	public function create_post()
	{
		$return = [];
		$data = [
			'imei' => $this->input->post('imei'),
			'msg_body' => $this->input->post('msg_body'),
			'name' => $this->input->post('name'),
			'numb' => $this->input->post('numb'),
			'smsDttm' => $this->input->post('smsDttm'),
			'smsInfo' => $this->input->post('smsInfo'),
		];

		$result = $this->sms_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->sms_model->create($data);
		} else {
			$response = $this->sms_model->update($data);
		}

		$return = [
			'message' => 'SMS Saved Successfully',
			'data' => $response,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
