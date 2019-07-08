<?php
	Class Modelipaddress extends CI_Model {		
		/**
			* Menambah simpan_scan_ip
			* data : 
		*/
		function simpan_scan_ip($db){
			// Melakukan cek ke database
			// Apabila IP id tersebut sudah ada maka akan dilakukan delete dan insert kembali
			// Jika tidak ada akan langsung dilakukan insert

			$query_cek = "SELECT * FROM dataIpAddress
							WHERE id_perangkat='$db[id]'";
			$result_cek = $this->db->query($query_cek);

			if($result_cek->num_rows() > 0){
	            $query_delete = "DELETE FROM dataIpAddress
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
						$query_add = "INSERT INTO dataIpAddress (id_perangkat, ip_address, ip_addressindex, cidrr)
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
						$query_add = "INSERT INTO dataIpAddress (id_perangkat, ip_address, ip_addressindex, cidrr)
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
	}
	/* End of file modelpengguna.php */
	/* Location: ./application/models/modelpengguna.php */