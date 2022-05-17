<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_model extends CI_Model {

	protected $table = 'device_registrations';

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
		$query = $this->db->get_where($this->table, $data)->row();

		if ($query) {
			return false;
		} else {
			return true;
		}
	}

}