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

	public function verbose($ok=1, $info="")
	{
		// failure to upload throws 400 error
		if ($ok == 0) { 
			http_response_code(400); 
		}
		
		// $return = [
		// 	'ok' => $ok,
		// 	'info' => $info,
		// ];
		// $this->response($return, REST_Controller::HTTP_BAD_REQUEST);
		die(json_encode(["ok"=>$ok, "info"=>$info]));
	}

	public function upload_post()
	{
		// invalid upload
		if (empty($_FILES) || $_FILES['file']['error']) {
			$this->verbose(0, "Failed to move uploaded file.");
		}
		// upload destination
		$filePath = FCPATH . "uploads";
		if (!file_exists($filePath)) {
		if (!mkdir($filePath, 0777, true)) {
			$this->verbose(0, "Failed to create $filePath");
		}
		}
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
		$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;
		// dealing with the chunks
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
		$in = @fopen($_FILES['file']['tmp_name'], "rb");
		if ($in) {
			while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
		} else {
			$this->verbose(0, "Failed to open input stream");
		}
		@fclose($in);
		@fclose($out);
		@unlink($_FILES['file']['tmp_name']);
		} else {
		$this->verbose(0, "Failed to open output stream");
		}
		// check if file was uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			rename("{$filePath}.part", $filePath);
		}
		$this->verbose(1, "Upload OK");
	}

	public function uploadtest_post()
    {
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		// Uncomment this one to fake upload time
		// usleep(5000);

		// Settings

		$targetDir = FCPATH . "uploads";
		//$targetDir = 'uploads';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds


		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}

		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	


		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}

			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}

		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}

		// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

    }
}
