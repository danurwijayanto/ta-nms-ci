<?php

	Class Squid_model extends CI_Model {	
		/**
			* Get Semua Log Squid 
		*/
		function get_log(){
			// $query = "SELECT * FROM squid_history";
			// $query = "SELECT a.*, b.*, d.nama_perangkat, c.nama_interface 
			// 			FROM data_ipaddress as a RIGHT JOIN squid_history as b 
			// 			on SUBSTRING_INDEX(a.ip_address, '.', 3) = SUBSTRING_INDEX(b.user_ip, '.', 3) 
			// 			LEFT JOIN data_interface as c on a.ip_addressindex = c.interface_index 
			// 			LEFT JOIN data_perangkat as d on c.id_perangkat = d.id_perangkat";
			$query = "SELECT a.*, b.*, d.nama_perangkat, c.nama_interface 
			 			FROM list_ip as e RIGHT JOIN squid_history as b 
			 			on e.ip_squid = b.user_ip
			 			LEFT JOIN data_ipaddress as a on e.ip_interface = a.ip_address
			 			LEFT JOIN data_interface as c on a.ip_addressindex = c.interface_index 
			 			LEFT JOIN data_perangkat as d on c.id_perangkat = d.id_perangkat";
	        $result = $this->db->query($query);
			return $result->result_array();
		}

		function popular_site(){
			#$query = "SELECT domain_tujuan, count(*) as cnt FROM squid_history GROUP BY domain_tujuan ORDER BY cnt DESC";
	        $query = "SELECT domain_tujuan FROM squid_history";
	        $result = $this->db->query($query);
			return $result->result_array();
		}

		# Start Fungsi untuk menghitung statistik popular site
		function select_userip(){
			$query = "SELECT user_ip FROM squid_history GROUP BY user_ip";
			$result = $this->db->query($query);
			return $result->result_array();
		}

		function count_domaintuj(){
			#$hasil = array();
			$userip = $this->select_userip();
			$query_result = array();
			$domhit = array();
			$domhit_fix = array();

			function url($data){
			    //GETTING DOMAIN USING PREG MATCH
			    // get host name from URL
			    preg_match('@^(?:http://)?([^/]+)@i', $data, $matches); $host = $matches[1];

			    // get last two segments of host name
			    preg_match('/[^.]+\.[^.]+$/', $host, $matches); 
			    return $domain = $matches[0];

			  }

			foreach ($userip as $ip) {
				// $query = "SELECT domain_tujuan, user_ip, count(domain_tujuan) as cnt FROM squid_history 
				// 		WHERE user_ip='$ip[user_ip]' GROUP BY domain_tujuan ORDER BY cnt DESC LIMIT 1";
				// $result = $this->db->query($query);
				// $result = $result->result_array();
				// foreach ($result as $row){
				// $query_result[] = array(
				// 						$row['domain_tujuan'],
				// 						$row['user_ip'],
				// 						$row['cnt'],
				// 					);
				// }
				// TEST QUERY
				$query = "SELECT domain_tujuan, user_ip FROM squid_history 
						WHERE user_ip='$ip[user_ip]'";
				// $query = "SELECT domain_tujuan, user_ip FROM squid_history as a , list_ip as b
				// 		WHERE b.ip_squid=a.user_ip AND user_ip='$ip[user_ip]'";
				$result = $this->db->query($query);
				$result = $result->result_array();



				foreach ($result as $pop) {  
	                //Memasukkak ke array baru    
	                array_push($domhit,url($pop['domain_tujuan']));
              	}
              	//Menghitung Jumlah Value Array yang Sama
              	$domhit = array_count_values($domhit);
				
				//Sort Array (Descending Order), According to Value - arsort()
              	arsort($domhit);
              	// print_r($domhit);
              	//membuat Array dengan Index dan Mengambil array pertama
              	// $i=0;
              	foreach ($domhit as $dom => $cnt) {
              	 	$domhit_fix[]=array(
              	 					$dom, 
              	 					$cnt
              	 				);
              		// if ($i==1){
              		// 	break;
              		// }else{
              		// 	$domain = $dom;
              		// 	$cnt = $cnt;
              		// }
              		
              		// // break;
              		// $i++;
              	 }
              	 // $domain  = 
				// print_r($domhit_fix[0][0]);


				// foreach ($userip as $row){
				$query_result[] = array(
									// $row['domain_tujuan'],
									$domhit_fix[0][0],
									$ip['user_ip'],
									$domhit_fix[0][1]
									// $row['cnt'], DOMHIT
								);
				// }	
				// Unset Array
				unset($domhit);
				unset($domhit_fix);
				$domhit = array();
				$domhit_fix = array();
			}	

			// print_r ($query_result);
			return  $query_result;
		}

		function get_namaif(){
			$userip2 = $this->count_domaintuj();
			// print_r($userip2);
			$query_result = array();
			
			foreach ($userip2 as $ip) {
				// $query = "SELECT a.nama_interface 
				// 		FROM data_interface  as a , data_ipaddress as b
				// 		WHERE SUBSTRING_INDEX('$ip[1]', '.', 3)=SUBSTRING_INDEX(b.ip_address, '.', 3) AND b.ip_addressindex=a.interface_index";
				$query = "SELECT a.nama_interface , d.nama_perangkat
						FROM data_interface  as a , data_ipaddress as b, list_ip as c, data_perangkat as d
						WHERE '$ip[1]'=c.ip_squid AND c.ip_interface=b.ip_address AND b.ip_addressindex=a.interface_index AND a.id_perangkat=d.id_perangkat";
				$result = $this->db->query($query);
				$result = $result->result_array();
				// print_r ($result);
				foreach ($result as $row){
					$query_result[] = array(
										'domain' => $ip[0],
										'ip_asal' => $ip[1],
										'hit' => $ip[2],
										'nama_if' => $row['nama_interface'],
										'nama_perangkat' => $row['nama_perangkat']
									);
				}
				// print_r($result);
				// $groups = array();
			 //    $key = 0;
			 //    foreach ($query_result as $item) {
			 //        $key = $item['nama_if'];
			 //        if (!array_key_exists($key, $groups)) {
			 //            $groups[$key] = array(
			 //                // 'id' => $item['evaluation_category_id'],
			 //                'nama_if' => $item['nama_if'],
			 //                'hit' => $item['hit'],
			 //            );
			 //        } else {
			 //            $groups[$key]['hit'] = $groups[$key]['hit'] + $item['hit'];
			 //            // $groups[$key]['itemMaxPoint'] = $groups[$key]['itemMaxPoint'] + $item['itemMaxPoint'];
			 //        }
			 //        $key++;
			 //    }
			 //    print_r($groups);

				
			}	
			$result1 = array();
			$test =  array();
			foreach ($query_result as $row)
				{
				  $result1[$row['nama_if']]['domain'] = $row['domain'];
				  $result1[$row['nama_if']]['ip_asal'] = $row['ip_asal'];
				  $result1[$row['nama_if']]['nama_if'] = $row['nama_if'];
				  @$result1[$row['nama_if']]['hit'] += $row['hit'];
				  @$result1[$row['nama_if']]['nama_perangkat'] = $row['nama_perangkat'];
				  // $result[$row['nama_if']]['hit'] += $row['hit'];
				  // $result[$row['nama_if']]['earn'] += $row['earn'];
				  // print_r(array($result1));
					// array_push($test,$result1);

				}
				$test= array_values($result1);
				// foreach ($test as $a) {
				// 	print_r($a);
				// 	# code...
				// }
				// print_r($test);
			// print_r ($query_result);
			return  $test;
		}
		# End Fungsi untuk menghitung statistik popular site

		// Controller welcome/cari_statistik
		function cari_statistik($data){
			// print_r($data['tawal']);

			// $query = "SELECT domain_tujuan, nama_interface FROM data_interface as a, data_ipaddress as b, squid_history as c
			// 		WHERE a.interface_index=b.ip_addressindex and SUBSTRING_INDEX(c.user_ip, '.', 3)=SUBSTRING_INDEX(b.ip_address, '.', 3) and a.interface_index = $data[id_if] and (DATE(c.waktu) BETWEEN '$data[tawal]' AND '$data[takhir]')";
			$query = "SELECT domain_tujuan, nama_interface FROM data_interface as a, data_ipaddress as b, squid_history as c, list_ip as d
					WHERE a.interface_index=b.ip_addressindex and c.user_ip=d.ip_squid and b.ip_address=d.ip_interface and a.interface_index = $data[id_if] and (DATE(c.waktu) BETWEEN '$data[tawal]' AND '$data[takhir]')";

			$result = $this->db->query($query);
			return $result->result_array();
		}


		function sinkron_ip(){
			$query1 = "SELECT *
				FROM `data_ipaddress`
				GROUP BY ip_address";
			$query2 = "SELECT * 
				FROM `squid_history` 
				GROUP BY user_ip";

			$result1 = $this->db->query($query1);
			$result2 = $this->db->query($query2);
			$dataip = $result1->result_array();
			$squid = $result2->result_array();
			$this->db->truncate('list_ip');

			// print_r($dataip);
			echo $this->fungsiku->IPisWithinCIDR('10.10.13.1','10.10.8.1/22');
			foreach ($squid as $squid) {
				foreach ($dataip as $dataip1) {
					if ($this->fungsiku->IPisWithinCIDR($squid['user_ip'],$dataip1['ip_address']."/".$dataip1['cidrr']) == TRUE){
						// echo $squid['user_ip']."  ||   ".$dataip1['ip_address']."<br>";
						$data = array(
							'ip_squid' => $squid['user_ip'],
							'ip_interface' => $dataip1['ip_address']
						);
						$query = $this->db->insert('list_ip',$data);
					}
					// echo $squid['user_ip']." || ".$dataip1['ip_address']."<br>";
				}
				# code...
			}

		}
	}	
	/* End of file squid_model.php */
	/* Location: ./application/models/squid_model.php */