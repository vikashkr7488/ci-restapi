<?php
require APPPATH . 'libraries/REST_Controller.php';

class Account extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('account_model');
    }

	public function create_post()
	{	
		$return = [];	
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);

		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$accounts = $payload['accounts'];
			for ($i=0; $i < count($accounts); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['acc_name'] = $accounts[$i]['acc_name'];
				$data[$i]['acc_type'] = $accounts[$i]['acc_type'];
				
				$result = $this->account_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->account_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
