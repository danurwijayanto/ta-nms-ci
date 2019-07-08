<?php
	Class Modelinterface extends CI_Model {		
		/**
			* Menambah artikel baru
			* data : 
		*/
		function get_allif(){
			$query = "SELECT * FROM dataInterface";
			$result = $this->db->query($query);

			return $result->result_array();
		}

		function get_statusif($data){
			$query = "SELECT * FROM dataInterface WHERE status='$data'";
	        $result = $this->db->query($query);
	        return $result->result_array();
		}

		function cek_interface($data){
			$query = "SELECT * FROM dataInterface WHERE id_perangkat=$data";
			$result = $this->db->query($query);
			if ($result->num_rows() > 0){
				return $result->result_array();
			}else{
				return 0;
			}
		}

		public  function cek_rrd($data){
			$id_if = $data['id_if'];
			$id_per = $data['id_per'];
			$id_rrd_name = $id_if."_".$id_per;
			$query = "SELECT id_rrd FROM dataInterface 
				WHERE interface_index=$id_if AND id_perangkat=$id_per";

			$result = $this->db->query($query);	
			$result = $result->result_array();
			// print_r($result[0]['id_rrd']);
			if ($result[0]['id_rrd']==$id_rrd_name) {
				return 1;
			}else {
				return 0;
			} 
		}

		function simpan_rrd($data){
			$id_if = $data['id_if'];
			$id_per = $data['id_per'];
			$id_rrd_name = $id_if."_".$id_per;
			//Simpan ke database
			
			$query = "UPDATE dataInterface SET id_rrd = '$id_rrd_name'
				WHERE interface_index=$id_if AND id_perangkat=$id_per";
			$result = $this->db->query($query);
		}

		function getbandwith($id){
			$query = "SELECT * 
						FROM dataInterface WHERE interface_index=$id AND id_perangkat=1";
			$result = $this->db->query($query);
			return $result->result_array();
		}


		function simpan_scan_if($db){
			// Melakukan cek ke database
			// Apabila interface id tersebut sudah ada maka akan dilakukan delete dan insert kembali
			// Jika tidak ada akan langsung dilakukan insert
			$ganti = array("INTEGER: ", "STRING: ", "(1)", "(2)");
			$query_cek = "SELECT * FROM dataInterface
							WHERE id_perangkat='$db[id]'";
			$result_cek = $this->db->query($query_cek);

			if($result_cek->num_rows() > 0){
	            $query_delete = "DELETE FROM dataInterface
	            					WHERE id_perangkat='$db[id]'";
	            $result_delete = $this->db->query($query_delete);

	            // Fungsi untuk menambahkan ke database setelah data lama dihapus
	            $panjang_if = count($db['id_if']);
				$a = array();
				for ($i=0; $i<$panjang_if; $i++){
					
					$if_index =  trim(str_replace($ganti,"",$db['id_if'][$i]));
					$if_name =  trim(str_replace($ganti,"",$db['nama_if'][$i]));
					$if_status = trim(str_replace($ganti,"",$db['status_if'][$i]));
					// $if_status = $db['status_if'][$i];

					//Edit ke database
					$query_add = "INSERT INTO dataInterface (interface_index, nama_interface, status, id_perangkat)
			    				  VALUES ($if_index, '$if_name', '$if_status', $db[id])";

			    	$result_add = $this->db->query($query_add);			
				}

	            if($this->db->affected_rows() > 0){
	            	return "Data Interface Berhasil diperbaharui";
	        	} else {
	            	return $this->db->error;
	        	}
			}else {

				// return FALSE;
				$panjang_if = count($db['id_if']);
				$a = array();
				for ($i=0; $i<$panjang_if; $i++){
			
					$if_index =  trim(str_replace("INTEGER: ","",$db['id_if'][$i]));
					$if_name =  trim(str_replace("STRING: ","",$db['nama_if'][$i]));
					$if_status = trim(str_replace("INTEGER: ","",$db['status_if'][$i]));
					// $if_status = $db['status_if'][$i];

					//Simpan ke database
					$query = "INSERT INTO dataInterface (interface_index, nama_interface, status, id_perangkat)
			    				  VALUES ($if_index, '$if_name', '$if_status', $db[id])";

			    	$result = $this->db->query($query);			
				}
				if($this->db->affected_rows() > 0){
		            return "Data Interface Berhasil ditambahkan";
		        } else {
	            	return $this->db->error;
	       	 	}
			}
		}
	}
	/* End of file modelpengguna.php */
	/* Location: ./application/models/modelpengguna.php */