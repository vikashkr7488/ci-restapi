<?php
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->library('form_validation');
	   $this->load->model('account_model');
    }

	public function create_post()
	{
		$this->form_validation->set_rules('imei', 'IMEI', 'required|trim|min_length[15]|max_length[15]|numeric');
		$this->form_validation->set_rules('acc_name', 'Account Name', 'required|trim');
		$this->form_validation->set_rules('acc_type', 'Account Type', 'required|trim');

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
				'acc_name' => $this->input->post('acc_name'),
				'acc_type' => $this->input->post('acc_type'),
			];

			$result = $this->account_model->create($data);

			$return = [
				'message' => 'Account Saved Successfully',
				'data' => $data,
			];
        }

		$this->response($return, REST_Controller::HTTP_CREATED);
	}

	public function index_get()
	{
		echo 'Hello';
	}
}
