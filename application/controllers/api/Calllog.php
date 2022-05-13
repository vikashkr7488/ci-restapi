<?php
require APPPATH . 'libraries/REST_Controller.php';

class Calllog extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->library('form_validation');
	   $this->load->model('calllog_model');
    }

	public function create_post()
	{
		$this->form_validation->set_rules('imei', 'IMEI', 'required|trim|min_length[15]|max_length[15]|numeric');
		$this->form_validation->set_rules('callDuration', 'Call Duration', 'required|trim');
		$this->form_validation->set_rules('callInfo', "Call Info", 'required|trim');
		$this->form_validation->set_rules('numb', "Number", 'required|trim');

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
				'callDuration' => $this->input->post('callDuration'),
				'callInfo' => $this->input->post('callInfo'),
				'numb' => $this->input->post('numb'),
			];

			$result = $this->calllog_model->create($data);

			$return = [
				'message' => 'Call Logs Saved Successfully',
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
