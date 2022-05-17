<?php
require APPPATH . 'libraries/REST_Controller.php';

class Device extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->library('form_validation');
	   $this->load->model('device_model');
    }

	public function registration_post()
	{
		$this->form_validation->set_rules('imei', 'IMEI', 'required|is_unique[device_registrations.imei]');
		$return = [];

		if ($this->form_validation->run() == FALSE) {
			$error = validation_errors();
			$return = [
				'message' => $error,
				'status' => false,
			];
        } else {			
			$data = [
			'android' => $this->input->post('android'),
			'device' => $this->input->post('device'),
			'deviceMobileNet' => $this->input->post('deviceMobileNet'),
			'deviceWifiNet' => $this->input->post('deviceWifiNet'),
			'imei' => $this->input->post('imei'),
			'macId' => $this->input->post('macId'),
			'mobileNum1' => $this->input->post('mobileNum1'),
			'mobileNum2' => $this->input->post('mobileNum2'),
			'secpatch' => $this->input->post('secpatch'),
			'gtoken' => $this->input->post('gtoken'),
			'ip_address' => $this->input->ip_address()
		];
		
		$result = $this->device_model->checkDuplicate($data);
		
		if ($result == true) {
			$response = $this->device_model->create($data);
		} else {
			$response = false;
		}

		$return = [
			'message' => 'Device Registered Successfully',
			'status' => $response,
		]; 
	}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
