<?php
	Class Auth extends CI_Model {		
		/**
			* Menambah artikel baru
			* data : 
		*/
		function auth_login($data) {
			//$salt = '123NgopoNdes**//';
			//encrypt password inputan lama
			$password = md5($data['password']);
			//Cek Password Lama
			$query = "SELECT *  
				FROM user
				WHERE email='$data[email]' AND password='$password'";
			$result = $this->db->query($query);
			// print_r($result);
			if ($result->num_rows() > 0) {
				return TRUE;
			}else {
				return FALSE;
			}
		}

		public function read_user_information($data) {
			// print_r($data);
			$query = "SELECT * 
				FROM user
				WHERE email='$data[email]'";
			
			$result = $this->db->query($query);
			
	        if($result->num_rows() == 1){
	            $query_result = array();
				foreach ($result->result_array() as $row){
					$query_result['id_user'] = $row['id_user'];
					$query_result['username'] = $row['username'];
					$query_result['email'] = $row['email'];
					// $query_result['password'] = $row['password'];
					// $query_result['id_kat'] = $row['id_kat'];
					$query_result['role'] = $row['role'];
				}
	            return $query_result;
	            // print_r($query_result);
			}else {
				return FALSE;
			}	
		}

		public function get_all_user(){
			$query = "SELECT * FROM user";
			$result = $this->db->query($query);

	        return $result->result_array();


		}

		public function cek_email(){
			$email = $this->input->post('email');
			$query = "SELECT email FROM user
				WHERE email='$email'";
			$result = $this->db->query($query);
	        $query_result = array();
			foreach ($result->result_array() as $row){
				$query_result['email'] = $row['email'];
			}
	        if($result->num_rows()==1){
	            return $query_result;
	        } else {
	            return FALSE;
	        }
		}
		public function get_userby_email($email){
			$query = "SELECT * FROM user
				WHERE email='$email'";
	        $result = $this->db->query($query);
	        $query_result = array();
			foreach ($result->result_array() as $row){
				$query_result['id'] = $row['id'];
				$query_result['nama_user'] = $row['nama_user'];
				$query_result['email'] = $row['email'];
				$query_result['password'] = $row['password'];
				$query_result['role'] = $row['role'];
			}
	        if($result->num_rows() == 1){
	            return $query_result;
			}else {
				return FALSE;
			}	
		}

		public function tambah_user($data){
			$query = $this->db->insert('user',$data);
			if ($this->db->affected_rows() > 0) {
				return "Data Berhasil Ditambahkan";
			}else {
				return $this->db->error;
			}
		}

		public function hapus_user($data){
			$query = "DELETE FROM user WHERE id_user=$data";
	        $result = $this->db->query($query);
	        if($this->db->affected_rows() > 0){
	            return "User Berhasil dihapus";
	        } else {
	            return $this->db->error;
	        }
		}

		public function get_user($data){
			$query = "SELECT * FROM user WHERE id_user=$data";
	        $result = $this->db->query($query);
	        return $result->result_array();
		}

		function simpan_edit_user($data){
			$query = "UPDATE user SET username='$data[username]', email='$data[email]', password='$data[password]', role='$data[role]'
				WHERE id_user=$data[id]";
	        $result = $this->db->query($query);
	        if($this->db->affected_rows() > 0){
	            return "Data Berhasil dipdate";
	        } else {
	            return $this->db->_error_message();
	        }
		}
	}
	/* End of file sma_sltg.php */
	/* Location: ./application/models/sma_sltg.php */