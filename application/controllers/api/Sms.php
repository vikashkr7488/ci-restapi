<?php
require APPPATH . 'libraries/REST_Controller.php';

class Sms extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->library('form_validation');
	   $this->load->model('sms_model');
    }

	public function create_post()
	{
		$this->form_validation->set_rules('imei', 'IMEI', 'required|trim|min_length[15]|max_length[15]|numeric');
		$this->form_validation->set_rules('msg_body', 'Message body', 'required|trim');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('numb', "Number", 'required|trim');
		$this->form_validation->set_rules('smsDttm', "SMS date and time", 'required|trim');
		$this->form_validation->set_rules('smsInfo', "SMS Info", 'required|trim');

		$return = [];
		if ($this->form_validation->run() == FALSE){
			$error = validation_errors();

			$return = [
				'inserted' => false,
				'message' => $error
			];
        } else {
        	// success
			$data = [
				'imei' => $this->input->post('imei'),
				'msg_body' => $this->input->post('msg_body'),
				'name' => $this->input->post('name'),
				'numb' => $this->input->post('numb'),
				'smsDttm' => $this->input->post('smsDttm'),
				'smsInfo' => $this->input->post('smsInfo'),
			];

			$result = $this->sms_model->create($data);

			$return = [
				'message' => 'SMS Saved Successfully',
				'data' => $result,
			];
        }

		$this->response($return, REST_Controller::HTTP_CREATED);
	}

	public function index_get()
	{
		echo 'Hello';
	}
}
