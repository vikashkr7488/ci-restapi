<?php
require APPPATH . 'libraries/REST_Controller.php';

class Contact extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->library('form_validation');
	   $this->load->model('contact_model');
    }

	public function create_post()
	{
		$this->form_validation->set_rules('imei', 'IMEI', 'required|trim|min_length[15]|max_length[15]|numeric');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('numb', "Number", 'required|trim|is_unique[contacts.numb]');

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
				'name' => $this->input->post('name'),
				'numb' => $this->input->post('numb')
			];

			$result = $this->contact_model->create($data);

			$return = [
				'message' => 'Contact Saved Successfully',
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
