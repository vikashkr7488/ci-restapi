<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model {

	protected $table = 'accounts';

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

	public function checkDuplicate($data)
	{
		$this->db->where($data);
		$query = $this->db->get($this->table);
		
		if ($query->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}

}