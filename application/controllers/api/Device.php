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
		$this->form_validation->set_rules('android', 'Android', 'required|trim');
		$this->form_validation->set_rules('device', 'Device', 'required|trim');
		$this->form_validation->set_rules('deviceMobileNet', "Device Mobile Net", 'required');
		$this->form_validation->set_rules('deviceWifiNet', 'Device Wifi Net', 'required');
		$this->form_validation->set_rules('imei', 'IMEI', 'required|trim|min_length[15]|max_length[15]|numeric|is_unique[device_registrations.imei]');
		$this->form_validation->set_rules('macId', 'Mac Id', 'required|trim');
		$this->form_validation->set_rules('mobileNum1', "Mobile Num 1", 'required|trim');
		$this->form_validation->set_rules('secpatch', 'Secpatch', 'required|trim');
		$this->form_validation->set_rules('gtoken', 'gtoken', 'required|trim');

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

			$result = $this->device_model->create($data);

			$return = [
				'message' => 'Device Registered Successfully',
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
