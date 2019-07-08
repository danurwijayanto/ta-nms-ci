<?php

	Class SNMP_model extends CI_Model {	

		
		function get_alldev(){
			$query = "SELECT * FROM data_perangkat";
			$result = $this->db->query($query);

			return $result->result_array();
		}

		function get_allif(){
			$query = "SELECT * FROM data_interface";
			$result = $this->db->query($query);

			return $result->result_array();
		}

		function simpan_perangkat($data) {
			$query = $this->db->insert('data_perangkat',$data);
			if ($this->db->affected_rows() > 0) {
				return "Data Berhasil Ditambahkan";
			}else {
				return $this->db->error;
			}
		}

		function hapus_perangkat($data){
			$query = "DELETE FROM data_perangkat WHERE id_perangkat=$data";
	        $result = $this->db->query($query);
	        if($this->db->affected_rows() > 0){
	            return "Perangkat Berhasil dihapus";
	        } else {
	            return $this->db->error;
	        }
		}

		function get_perangkat($data){
			$query = "SELECT * FROM data_perangkat WHERE id_perangkat=$data";
	        $result = $this->db->query($query);
	        return $result->result_array();
		}

		function get_statusperangkat($data){
			$query = "SELECT * FROM data_perangkat WHERE status='$data'";
	        $result = $this->db->query($query);
	        return $result->result_array();
		}

		function get_statusif($data){
			$query = "SELECT * FROM data_interface WHERE status='$data'";
	        $result = $this->db->query($query);
	        return $result->result_array();
		}

		function simpan_edit_perangkat($data){
			$query = "UPDATE data_perangkat SET nama_perangkat='$data[nama_perangkat]', ip_address='$data[ip_address]', lokasi='$data[lokasi]', community='$data[community]', os='$data[os]'
				WHERE id_perangkat=$data[id]";
	        $result = $this->db->query($query);
	        if($this->db->affected_rows() > 0){
	            return "Data Berhasil dipdate";
	        } else {
	            return $this->db->error;
	        }
		}

		function rubah_statperangkat($data, $mode){
			

			if ($mode == 0){
				$status_if_baru = array(
	               'status' => $data['status_if_baru']
	            );
				$where = "id_perangkat = $data[id] AND interface_index = $data[if_index]";
				$this->db->where($where);
				// $this->db->where('id_perangkat', $data['id'], 'interface_index', $data['if_index']);
				$this->db->update('data_interface', $status_if_baru); 
			}else if ($mode == 1){
				$status_per_baru = array(
	               'status' => $data['status_per_baru']
	            );
				$this->db->where('id_perangkat', $data['id']);
				$this->db->update('data_perangkat', $status_per_baru); 
			}else {
				//update status perangkat
				$status_per_baru = array(
	               'status' => $data['status_per_baru']
	            );
				$this->db->where('id_perangkat', $data['id']);
				$this->db->update('data_perangkat', $status_per_baru); 

				//update status interface
				$status_if_baru = array(
	               'status' => $data['status_if_baru']
	            );
				$where = "id_perangkat = $data[id] AND interface_index = $data[if_index]";
				$this->db->where($where);
				// $this->db->where('id_perangkat', $data['id'], 'interface_index', $data['if_index']);
				$this->db->update('data_interface', $status_if_baru); 
				
			}
			return;
		}

		function cek_interface($data){
			$query = "SELECT * FROM data_interface WHERE id_perangkat=$data";
			$result = $this->db->query($query);
			if ($result->num_rows() > 0){
				return $result->result_array();
			}else{
				return 0;
			}
		}

		function get_interface_active(){
			$query = "SELECT nama_interface, interface_index
				FROM  data_interface, data_ipaddress
				WHERE data_interface.interface_index=data_ipaddress.ip_addressindex AND data_interface.id_perangkat=data_ipaddress.id_perangkat GROUP BY nama_interface";

			#$query = "SELECT nama_interface FROM data_interface GROUP BY nama_interface";
			$result = $this->db->query($query);
	        return $result->result_array();
		}

		function get_data_if($data){
			$query = "SELECT *
				FROM  data_interface LEFT JOIN data_ipaddress
				ON data_interface.interface_index=data_ipaddress.ip_addressindex AND data_interface.id_perangkat=data_ipaddress.id_perangkat
				WHERE data_interface.id_perangkat=$data";
			$result = $this->db->query($query);
	        return $result->result_array();
		}

		// Controller welcome/detail_if
		public function get_detail_if($data){
			$query = "SELECT *
				FROM  data_interface 
				WHERE interface_index=$data[id_if] AND id_perangkat=$data[id_per]";
			$result = $this->db->query($query);
	        return $result->result_array();
		}

		function simpan_scan_if($db){
			// Melakukan cek ke database
			// Apabila interface id tersebut sudah ada maka akan dilakukan delete dan insert kembali
			// Jika tidak ada akan langsung dilakukan insert
			$ganti = array("INTEGER: ", "STRING: ", "(1)", "(2)");
			$query_cek = "SELECT * FROM data_interface
							WHERE id_perangkat='$db[id]'";
			$result_cek = $this->db->query($query_cek);

			if($result_cek->num_rows() > 0){
	            $query_delete = "DELETE FROM data_interface
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
					$query_add = "INSERT INTO data_interface (interface_index, nama_interface, status, id_perangkat)
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
					$query = "INSERT INTO data_interface (interface_index, nama_interface, status, id_perangkat)
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

		function simpan_scan_ip($db){
			// Melakukan cek ke database
			// Apabila IP id tersebut sudah ada maka akan dilakukan delete dan insert kembali
			// Jika tidak ada akan langsung dilakukan insert

			$query_cek = "SELECT * FROM data_ipaddress
							WHERE id_perangkat='$db[id]'";
			$result_cek = $this->db->query($query_cek);

			if($result_cek->num_rows() > 0){
	            $query_delete = "DELETE FROM data_ipaddress
	            					WHERE id_perangkat='$db[id]'";
	            $result_delete = $this->db->query($query_delete);

	            // Fungsi untuk menambahkan ke database setelah data lama dihapus
	            $panjang_ip = count($db['list_ip']);
				$a = array();
				for ($i=0; $i<$panjang_ip; $i++){
						$netmask = trim(str_replace("IpAddress: ","",$db['netmask'][$i]));
						//echo trim_er("IpAddress: ",$scan_ip['list_ip'][$i]).'||'.trim_er("INTEGER: ",$scan_ip['ip_index'][$i]).'<br>';
						$list_ipp = trim(str_replace("IpAddress: ","",$db['list_ip'][$i]));
						$ip_indexx = trim(str_replace("INTEGER: ","",$db['ip_index'][$i]));
						$cidr = $this->fungsiku->maskToCIDR($netmask);
						//Simpan ke database
						$query_add = "INSERT INTO data_ipaddress (id_perangkat, ip_address, ip_addressindex, cidrr)
			    				VALUES ($db[id], '$list_ipp', $ip_indexx, $cidr)";
						
			    	$result_add = $this->db->query($query_add);			
				}

	            if($this->db->affected_rows() > 0){
	            	return "Data IP Berhasil diperbaharui";
	        	} else {
	            	return $this->db->error;
	        	}
			}else {

				// Fungsi untuk menambahkan ke database setelah data lama dihapus
	            $panjang_ip = count($db['list_ip']);
				$a = array();
				for ($i=0; $i<$panjang_ip; $i++){

						//echo trim_er("IpAddress: ",$scan_ip['list_ip'][$i]).'||'.trim_er("INTEGER: ",$scan_ip['ip_index'][$i]).'<br>';
						$list_ipp = trim(str_replace("IpAddress: ","",$db['list_ip'][$i]));
						$ip_indexx = trim(str_replace("INTEGER: ","",$db['ip_index'][$i]));
						$cidr = $this->fungsiku->maskToCIDR($netmask);
						//Simpan ke database
						$query_add = "INSERT INTO data_ipaddress (id_perangkat, ip_address, ip_addressindex, cidrr)
			    				VALUES ('$db[id]', '$list_ipp', '$ip_indexx', $cidr)";
						
			    	$result_add = $this->db->query($query_add);			
				}

	            if($this->db->affected_rows() > 0){
	            	return "Data IP Berhasil diperbaharui";
	        	} else {
	            	return $this->db->error;
	        	}
			}
		}

		public  function cek_rrd($data){
			$id_if = $data['id_if'];
			$id_per = $data['id_per'];
			$id_rrd_name = $id_if."_".$id_per;
			$query = "SELECT id_rrd FROM data_interface 
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
			
			$query = "UPDATE data_interface SET id_rrd = '$id_rrd_name'
				WHERE interface_index=$id_if AND id_perangkat=$id_per";
			$result = $this->db->query($query);
			// if($this->db->affected_rows() > 0){
	  //           return "Data Interface Berhasil ditambahkan";
	  //       } else {
   //          	return $this->db->error;
   //     	 	}	
		}

		function get_rrd_details(){
			$query = "SELECT * FROM data_interface as a
				 INNER JOIN data_ipaddress as b
				ON b.id_perangkat=a.id_perangkat AND id_rrd !='' AND b.ip_addressindex=a.interface_index INNER JOIN data_perangkat as c
				ON b.id_perangkat=c.id_perangkat";

			$result = $this->db->query($query);
			return $result->result_array();
			
		}

		function cek_perubahan(){
			$query = "SELECT *
						FROM perangkat_audit AS a
						LEFT JOIN data_perangkat AS b ON a.id_perangkat = b.id_perangkat
						LEFT JOIN data_interface AS c ON a.id_interface = c.interface_index";
			$result = $this->db->query($query);
			if ($result->num_rows() > 0){
				return $result->result_array();
			}else{
				return 0;
			}
			
		}

		function drop_perubahan(){
			$this->db->truncate('perangkat_audit'); 


			return ;
		}

		function getbandwith($id){
			$query = "SELECT * 
						FROM data_interface WHERE interface_index=$id AND id_perangkat=1";
			$result = $this->db->query($query);
			return $result->result_array();

		}

	}	
	/* End of file snmp_model.php */
	/* Location: ./application/models/squid_model.php */