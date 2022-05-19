<?php
require APPPATH . 'libraries/REST_Controller.php';

class Filetree extends REST_Controller {

    public function __construct()
	{
       parent::__construct();
	   $this->load->model('filetree_model');
    }

	public function create_post()
	{
		$return = [];
		$rdata = $this->input->post('rdata');
		$payload = json_decode($rdata, true);
		
		if (isset($payload)) {
			$imei = $payload['infodevice']['imei'];
			$fileTree = $payload['fileTree'];
			for ($i=0; $i < count($fileTree); $i++) {   
				$data[$i]['imei'] = $imei;
				$data[$i]['fileName'] = $fileTree[$i]['fileName'];
				$data[$i]['fileSize'] = $fileTree[$i]['fileSize'];
				$data[$i]['isFileHidden'] = $fileTree[$i]['isFileHidden'];
				$data[$i]['lastModified'] = $fileTree[$i]['lastModified'];
				$data[$i]['path'] = $fileTree[$i]['path'];
				
				$result = $this->filetree_model->checkDuplicate($data[$i]);

				if ($result == true) {
					$response = $this->filetree_model->create($data[$i]);
					$return = 'mn';
				} else {
					$return = 'nop';
				} 
			}
		}

		$this->response($return, REST_Controller::HTTP_OK);
	}
}
