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

	public function update(array $data)
	{
		$this->db->where($data);
		return $this->db->update($this->table, $data);
	}

	public function checkDuplicate($data)
	{
		$query = $this->db->get_where($this->table, $data)->row();

		if ($query) {
			return false;
		} else {
			return true;
		}
	}

}