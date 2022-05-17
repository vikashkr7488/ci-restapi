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
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);
		
		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$contactData = $payload['contactData'];
			for ($i=0; $i < count($contactData); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['name'] = $contactData[$i]['name'];
				$data[$i]['numb'] = $contactData[$i]['numb'];
				
				$result = $this->contact_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->contact_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
