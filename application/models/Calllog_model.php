<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calllog_model extends CI_Model {

	protected $table = 'call_logs';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function create(array $data)
	{
		if (empty($data)) {
			return;
		}

		return $this->db->insert($this->table, $data);
	}

}