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
		$infodevice = $this->input->post('infodevice');
		$accounts = $this->input->post('accounts');
		$infodevic = json_decode($infodevice, true);
		$account = json_decode($accounts, true);

		if (isset($accounts)) {
			foreach ($account as $value) {
				for ($i=0; $i < count($value); $i++) 
				{   
					$data[$i]['imei'] = $infodevic['infodevice']['imei'];
					$data[$i]['acc_name'] = $value[$i]['acc_name'];
					$data[$i]['acc_type'] = $value[$i]['acc_type'];

					$result = $this->account_model->checkDuplicate($data[$i]);

					if ($result == true) {
						$response = $this->account_model->create($data[$i]);
					} else {
						$response = false;
					} 
				}
			}
		}

		$return = [
			'message' => 'Account Saved Successfully',
			'status' => $response ?? false,
		];

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
