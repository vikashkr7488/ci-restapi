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
		$infodevice = $this->input->post('infodevice');
		$contactData = $this->input->post('contactData');
		$infodevic = json_decode($infodevice, true);
		$contact = json_decode($contactData, true);

		if (isset($contact)) {
			foreach ($contact as $value) {
				for ($i=0; $i < count($value); $i++) 
				{   
					$data[$i]['imei'] = $infodevic['infodevice']['imei'];
					$data[$i]['name'] = $value[$i]['name'];
					$data[$i]['numb'] = $value[$i]['numb'];

					$result = $this->contact_model->checkDuplicate($data[$i]);

					if ($result == true) {
						$response = $this->contact_model->create($data[$i]);
					} else {
						$response = false;
					} 
				}
			}
		}

		$return = [
			'message' => 'Contact Saved Successfully',
			'status' => $response ?? false,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
