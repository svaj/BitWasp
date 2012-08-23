<?php 

class Users_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
	}

	public function getLoginInfo($userName = FALSE){
		if($userName === FALSE){
			return NULL;
		} else {
			$this->db->select('id,userSalt, userHash, userRole');
			$query = $this->db->get_where('users', array('userName' => $userName));

			if($query->num_rows() > 0){
				return $query->row_array();
			} else {
				return false;
			}
		}
	}

	public function addUser($userData){
		$query = $this->db->insert('users',$userData);
		if($query){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function setLastLog($username){
		$userdata = array('lastLog' => time());
		$this->db->where('username',$username);
		$query = $this->db->update('users',$userdata);
		if($query){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkPass($username,$hash){
		$this->db->select('password');
		$query = $this->db->get_where('users', array(	'userName' => $username,
								'password' => $hash ));
		if($query->num_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}



        //Load the requested user from the database by their the specified field.
        public function get_user($user = FALSE)
        {
		//Select these fields from the database
		$this->db->select('id, userName, userRole, userHash, rating, timeRegistered');

                //Check what field has been provide, and query database using that field.
                if (isset($user['userHash'])) {
			$query = $this->db->get_where('users', array('userHash' => $user['userHash']));
		} elseif (isset($user['id'])) {
			$query = $this->db->get_where('users', array('id' => $user['id']));
		} elseif (isset($user['userName'])) {
			$query = $this->db->get_where('users', array('userName' => $user['userName']));
		} else {
			return FALSE; //No suitable field found.
		}

		//If there is a matching row, it is returned to the user
		if($query->num_rows() > 0){
	                return $query->row_array();
		} else {
			return false;
		}
        }

	//Retrive this users public key.
        public function get_pubKey_by_id($id = FALSE)
        {

                //If no user is specified, return nothing.
                if ($id === FALSE) {
                        return NULL;
                }

                //Otherwise, load the public key which corresponds to this ID.
                $this->db->select('key');
                $query = $this->db->get_where('publicKeys', array('userId' => $id));
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			return $result['key'];
		}
		return NULL;
        }





}
