<?php

	Class Modelsquid extends CI_Model {	
		/**
			* Get Semua Log Squid 
		*/
		function get_log(){
			// $query = "SELECT * FROM dataLogSquid";
			// $query = "SELECT a.*, b.*, d.nama_perangkat, c.nama_interface 
			// 			FROM dataIpAddress as a RIGHT JOIN dataLogSquid as b 
			// 			on SUBSTRING_INDEX(a.ip_address, '.', 3) = SUBSTRING_INDEX(b.user_ip, '.', 3) 
			// 			LEFT JOIN dataInterface as c on a.ip_addressindex = c.interface_index 
			// 			LEFT JOIN dataPerangkat as d on c.id_perangkat = d.id_perangkat";
			$query = "SELECT a.*, b.*, d.nama_perangkat, c.nama_interface 
			 			FROM list_ip as e RIGHT JOIN dataLogSquid as b 
			 			on e.ip_squid = b.user_ip
			 			LEFT JOIN dataIpAddress as a on e.ip_interface = a.ip_address
			 			LEFT JOIN dataInterface as c on a.ip_addressindex = c.interface_index 
			 			LEFT JOIN dataPerangkat as d on c.id_perangkat = d.id_perangkat
			 			WHERE a.id_perangkat = c.id_perangkat";
	        $result = $this->db->query($query);
			return $result->result_array();
			// print_r($result->result_array());
		}

		function popular_site(){
			#$query = "SELECT domain_tujuan, count(*) as cnt FROM dataLogSquid GROUP BY domain_tujuan ORDER BY cnt DESC";
	        $query = "SELECT domain_tujuan FROM dataLogSquid";
	        $result = $this->db->query($query);
			return $result->result_array();
			// print_r($result->result_array());
		}

		# Start Fungsi untuk menghitung statistik popular site
		function select_userip(){
			$query = "SELECT user_ip FROM dataLogSquid GROUP BY user_ip";
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
				// $query = "SELECT domain_tujuan, user_ip, count(domain_tujuan) as cnt FROM dataLogSquid 
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
				$query = "SELECT domain_tujuan, user_ip FROM dataLogSquid 
						WHERE user_ip='$ip[user_ip]'";
				// $query = "SELECT domain_tujuan, user_ip FROM dataLogSquid as a , list_ip as b
				// 		WHERE b.ip_squid=a.user_ip AND user_ip='$ip[user_ip]'";
				$result = $this->db->query($query);
				$result = $result->result_array();



				foreach ($result as $pop) {  
	                //Memasukkak ke array baru    
	                // $host = parse_url($log['domain_tujuan']);
                 //                echo @$host['host'];
	                // array_push($domhit,url($pop['domain_tujuan']));
	                array_push($domhit,parse_url($pop['domain_tujuan'],PHP_URL_HOST));
              	}
              	//Menghitung Jumlah Value Array yang Sama
              	// print_r($domhit);
              	$domhit = @array_count_values($domhit);
				
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
				// 		FROM dataInterface  as a , dataIpAddress as b
				// 		WHERE SUBSTRING_INDEX('$ip[1]', '.', 3)=SUBSTRING_INDEX(b.ip_address, '.', 3) AND b.ip_addressindex=a.interface_index";
				$query = "SELECT a.nama_interface , d.nama_perangkat
						FROM dataInterface  as a , dataIpAddress as b, list_ip as c, dataPerangkat as d
						WHERE '$ip[1]'=c.ip_squid 
						AND c.ip_interface=b.ip_address 
						AND b.ip_addressindex=a.interface_index 
						AND a.id_perangkat=d.id_perangkat
						AND b.id_perangkat=d.id_perangkat";
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
			// print_r($query_result);
			// Makasih NUR, u so pro la
			$result1 = array();
			$test =  array();
			$hitung = array();
			$final = array();
			foreach ($query_result as $item) {
				if (!key_exists($item['nama_if'], $hitung)){
					$hitung[$item['nama_if']] = array();
				}
				if (!key_exists($item['domain'], $hitung[$item['nama_if']])){
					$hitung[$item['nama_if']][$item['domain']] = intval($item['hit']);
					$hitung[$item['nama_if']]['nama_perangkat'] = $item['nama_perangkat'];
				}else{
					$hitung[$item['nama_if']][$item['domain']] += intval($item['hit']);
					$hitung[$item['nama_if']]['nama_perangkat'] = $item['nama_perangkat'];
				}
			}
			// End fungsi by NUR
			foreach ($hitung as $key => $value) {
				arsort($hitung[$key]);
				// echo $key."<br>";
				$i = 0;
				foreach ( $hitung[$key] as $domain => $value1) {
					
					if ($i==1) {
						break;
					}else{
						$final[] = array(
							'interface' => $key,
							'nama_domain' => $domain,
							'hit' => $value1,
							'nama_perangkat' => $hitung[$key]['nama_perangkat']
							
						);
						$i++;
					}
				}
			}

			// print_r($final);
			


			// print_r(array_column($query_result, 'nama_if'));
			// print_r(array_unique(array_column($query_result, 'nama_if')));
			// print_r(array_column($query_result, 'nama_if'));
			// print_r(array_column($query_result, 'domain'));
			// $domain = array_column($query_result, 'domain', 'nama_if');
			// print_r($domain);
			// $kelompok_if = array();
			// foreach ($query_result as $a) {
			// 	$id = $a['nama_if'];
			//   	if (isset($a['nama_if'])) {
			//     	$kelompok_if[$id][] = $a;
			//   	} else {
			//      	$kelompok_if[$id] = array($a);
			//   	}
			// }

			// Bye me gagal
			// foreach ($query_result as $row)
			// {
			//   $result1[$row['nama_if']]['domain'] = $row['domain'];
			//   $result1[$row['nama_if']]['ip_asal'] = $row['ip_asal'];
			//   $result1[$row['nama_if']]['nama_if'] = $row['nama_if'];
			//   @$result1[$row['nama_if']]['hit'] += $row['hit'];
			//   @$result1[$row['nama_if']]['nama_perangkat'] = $row['nama_perangkat'];

			// }
			// $test= array_values($result1);
			//End bye me

			return  $final;//431
		}
		# End Fungsi untuk menghitung statistik popular site

		// Controller welcome/cari_statistik
		function cari_statistik($data){
			// print_r($data['tawal']);

			// $query = "SELECT domain_tujuan, nama_interface FROM dataInterface as a, dataIpAddress as b, dataLogSquid as c
			// 		WHERE a.interface_index=b.ip_addressindex and SUBSTRING_INDEX(c.user_ip, '.', 3)=SUBSTRING_INDEX(b.ip_address, '.', 3) and a.interface_index = $data[id_if] and (DATE(c.waktu) BETWEEN '$data[tawal]' AND '$data[takhir]')";
			$query = "SELECT domain_tujuan, nama_interface FROM dataInterface as a, dataIpAddress as b, dataLogSquid as c, list_ip as d
					WHERE a.interface_index=b.ip_addressindex and c.user_ip=d.ip_squid and b.ip_address=d.ip_interface and a.interface_index = $data[id_if] and (DATE(c.waktu) BETWEEN '$data[tawal]' AND '$data[takhir]')";

			$result = $this->db->query($query);
			return $result->result_array();
		}


		function sinkron_ip(){
			$query1 = "SELECT *
				FROM `dataIpAddress` WHERE id_perangkat=1
				GROUP BY ip_address";
			$query2 = "SELECT * 
				FROM `dataLogSquid` 
				GROUP BY user_ip";

			$result1 = $this->db->query($query1);
			$result2 = $this->db->query($query2);
			$dataip = $result1->result_array();
			$squid = $result2->result_array();
			$this->db->truncate('list_ip');

			// print_r($dataip);
			// echo $this->fungsiku->IPisWithinCIDR('10.10.13.1','10.10.8.1/22');
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
	/* End of file modelSquid.php */
	/* Location: ./application/models/modelSquid.php */