
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Controloperasi extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function cek_status(){

		$this->load->library('email');
		$this->load->library('fungsiku');
		

		$semua_perangkat = $this->modelperangkat->get_alldev();
		foreach ($semua_perangkat as $a) {
			# code...
			$status_per = $this->fungsiku->ping($a['ip_address']);
			// print_r($status_per."<br>");
			// Jika status perangkat up dan status perangkat database up 
			if (($a['status'] == "Up") && ($status_per == "Up")){
				// Cek Protocol SNMP pada perangkat
				if ($a['ver_snmp'] == '1'){
					// @$cek = snmpwalk($a['ip_address'], $a['community'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v 1 -c '.$a['community'].' -Oqv '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v '.$a['ver_snmp'].' -c '.$a['community'].' -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}else if ($a['ver_snmp'] == '2'){
					// @$cek = snmp2_walk($a['ip_address'], $a['community'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v 2c -c '.$a['community'].' -Oqv '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v '.$a['ver_snmp'].'c -c '.$a['community'].' -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}else{
					// @$cek = snmp3_walk($a['ip_address'], $a['community'], $a['type'], $a['authprot'], $a['authpass'], $a['encryptprot'], $a['encryptpass'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v3 -l '.$a['type'].' -u '.$a['community'].' -a '.$a['authprot'].' -A '.$a['authpass'].'  -x '.$a['encryptprot'].' -X '.$a['encryptpass'].' '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v3 -l '.$a['type'].' -u '.$a['community'].' -Oqv -a '.$a['authprot'].' -A '.$a['authpass'].'  -x '.$a['encryptprot'].' -X '.$a['encryptpass'].' '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}
				if (empty($cek)){
					$konten =  "Peringatan !! SNMP perangkat ".$a['nama_perangkat']." tidak bisa diakses <br><br>";
				}else {
					$konten =  " ";
					// Mencari interface sesuai dengan perangkat itu 
					$data_if = $this->modelperangkat->cek_interface($a['id_perangkat']);
					if ($data_if != 0){
						foreach ($data_if as $if) {
							// mencari status interface berdasarkan $data_if['interface_index'] dan $status_per['ip_address']
							$status_if = exec($snmp_query.$if['interface_index']);

							// print_r($status_if);
							// Menyimpan ke dalam array baru yaitu array $data
							$data = array(
								'id' => $a['id_perangkat'],
								'status_if_baru' => $status_if,
								'if_index' => $if['interface_index']
							);

							 // print_r($data);
							// Membandingkan status interface lama dengan yang baru kemudian
							// menyimpannya dalam database
							if ($status_if != $if['status']){
								$this->modelperangkat->rubah_statperangkat($data, 0);
							}
						}
					}
				}
			}else if (($a['status'] == "Up") && ($status_per == "Down")){
				// Jika status perangkat dari up menjadi down maka tidak dilakukan pengeceka interface
				$data = array(
						'id' => $a['id_perangkat'],
						'status_per_baru' => $status_per
					);
				// print_r($data['id']."<br>");
				// print_r( $data." <br>");

				// Merubah status perangkat

				$this->modelperangkat->rubah_statperangkat($data,1);

			}else if (($a['status'] == "Down") && ($status_per == "Up")){
				// Mencari interface sesuai dengan perangkat itu 
				$status_per = $this->fungsiku->ping($a['ip_address']);
				if ($a['ver_snmp'] == '1'){
					// @$cek = snmpwalk($a['ip_address'], $a['community'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v 1 -c '.$a['community'].' -Oqv '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v '.$a['ver_snmp'].' -c '.$a['community'].' -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}else if ($a['ver_snmp'] == '2'){
					// @$cek = snmp2_walk($a['ip_address'], $a['community'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v 2c -c '.$a['community'].' -Oqv '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v '.$a['ver_snmp'].'c -c '.$a['community'].' -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}else{
					// @$cek = snmp3_walk($a['ip_address'], $a['community'], $a['type'], $a['authprot'], $a['authpass'], $a['encryptprot'], $a['encryptpass'], ".1.3.6.1.2.1.2.2.1.1");
					@$cek = exec('/usr/local/bin/snmpget -v3 -l '.$a['type'].' -u '.$a['community'].' -a '.$a['authprot'].' -A '.$a['authpass'].'  -x '.$a['encryptprot'].' -X '.$a['encryptpass'].' '.$a['ip_address'].' .1.3.6.1.2.1.1.3.0');
					$snmp_query = '/usr/local/bin/snmpget -v3 -l '.$a['type'].' -u '.$a['community'].' -Oqv -a '.$a['authprot'].' -A '.$a['authpass'].'  -x '.$a['encryptprot'].' -X '.$a['encryptpass'].' '.$a['ip_address'].' IF-MIB::ifAdminStatus.';
				}
				if (empty($cek)){
					$konten =  "Peringatan !! SNMP perangkat ".$a['nama_perangkat']." tidak bisa diakses <br><br>";
					$data = array(
							'id' => $a['id_perangkat'],
							'status_per_baru' => $status_per,
						);
				}else {
					// Mencari interface sesuai dengan perangkat itu 
					$konten =  " ";
					$data_if = $this->modelperangkat->cek_interface($a['id_perangkat']);
					if ($data_if != 0){
						foreach ($data_if as $if) {
							// mencari status interface berdasarkan $data_if['interface_index'] dan $status_per['ip_address']
							$status_if = exec($snmp_query.$if['interface_index']);

							// print_r($status_if);
							// Menyimpan ke dalam array baru yaitu array $data
							$data = array(
								'id' => $a['id_perangkat'],
								'status_if_baru' => $status_if,
								'status_per_baru' => $status_per,
								'if_index' => $if['interface_index']
							);

							 // print_r($data);
							// Membandingkan status interface lama dengan yang baru kemudian
							// menyimpannya dalam database
							if ($status_if != $if['status']){
								$this->modelperangkat->rubah_statperangkat($data, 2);
							}
							// else {
							// 	$data = array(
							// 			'id' => $a['id_perangkat'],
							// 			'status_per_baru' => $status_per,
							// 		);
							// }
						}
					}else {
						$data = array(
							'id' => $a['id_perangkat'],
							'status_per_baru' => $status_per,
						);
					}
				}






				// $data_if = $this->modelperangkat->cek_interface($a['id_perangkat']);
				// //Melakukan pengecekan apakan mempunyai interface atau tidak
				// if ($data_if != 0){
				// 	foreach ($data_if as $if) {
				// 		// echo $if['interface_index']."<br>";

				// 		// mencari status interface berdasarkan $data_if['interface_index'] dan $status_per['ip_address']
				// 		$status_if = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.'.$if['interface_index'].'');
				// 		// echo $status_baru. "||" .$if['status']."<br>";

				// 		// Menyimpan ke dalam array baru yaitu array $data
				// 		$data = array(
				// 			'id' => $a['id_perangkat'],
				// 			'status_if_baru' => $status_if,
				// 			'status_per_baru' => $status_per,
				// 			'if_index' => $if['interface_index']
				// 		);

				// 		// Membandingkan status interface lama dengan yang baru kemudian
				// 		// menyimpannya dalam database
				// 		if ($status_if != $if['status']){
				// 			$this->modelperangkat->rubah_statperangkat($data, 2);
				// 		}
				// 	}

				// }else {
				// 	$data = array(
				// 			'id' => $a['id_perangkat'],
				// 			'status_per_baru' => $status_per,
				// 		);
				// }
				// print_r($a['id_perangkat']);
				// echo "beda 2 <br>";
				// print_r("<br>".$a['id_perangkat']." || ".$a['status']."||" . $status_per. "<br>");
				// print_r($data);
				$this->modelperangkat->rubah_statperangkat($data, 1);
			}else if (($a['status'] == "Down") && ($status_per == "Down")){
				// print_r("down<br>");
			}


			// }
		} 
		//End Foreach

		// Proses pengiriman Email
		$data_perubahan = $this->modelperangkat->cek_perubahan();
		$data_notif = $this->modelpengguna->cek_notif();
		if ($data_perubahan == 0){
			print_r("database_kosong");
		}else{
			$konten .= " 	
					Terjadi Perubahan Status Perangkat Pada : <br><br>
					<table border='1'>
						<tr>
							<th>No</th>
							<th>Perangkat</th>
							<th>Status Lama</th>
							<th>Status Baru</th>
							<th>Waktu</th>
						</tr>
			";

			foreach ($data_perubahan as $a) {
				if ($a['nama_interface'] == NULL){
					$konten .= '<tr>
								<td>'.$a['id_pa'].'</td>
								<td>'.$a['nama_perangkat'].'</td>
								<td>'.$a['status_lama'].'</td>
								<td>'.$a['status_baru'].'</td>
								<td>'.$a['waktu'].'</td>
							</tr>';
				}
				
			};
			$konten .= "</table><br><br><br>Terjadi Perubahan Status Interface Pada : <br><br>
						<table border='1'>
									<tr>
										<th>No</th>
										<th>Perangkat</th>
										<th>Interface</th>
										<th>Status Lama</th>
										<th>Status Baru</th>
										<th>Waktu</th>
									</tr>
						";
			foreach ($data_perubahan as $b) {
				if ($b['nama_interface'] != NULL){
					$konten .= '<tr>
								<td>'.$b['id_pa'].'</td>
								<td>'.$b['nama_perangkat'].'</td>
								<td>'.$b['nama_interface'].'</td>
								<td>'.$b['status_lama'].'</td>
								<td>'.$b['status_baru'].'</td>
								<td>'.$b['waktu'].'</td>
							</tr>';
				}
				
			};
			$konten .= '</table>';

			foreach ($data_notif as $data_notif) {
				$this->email->from('mobinity.fx@gmail.com', 'NMS FSM UNDIP');
				$this->email->to($data_notif['email']); 
				$this->email->subject('Notifikasi Status Perangkat dan Interface ');
				$this->email->message($konten);
				$berhasil = $this->email->send();	 
			}

			// print_r($konten);
			$reportToLog = "\r\n[".date('j F Y, H:i:s')."]\t\t: ";
				
			if (!$berhasil) {
				$reportToLog .= "Mailer Error!";
				// $data['submitErrors'] = "Maaf, gagal mengirim email";
				print_r( "Maaf, gagal mengirim email");
			} else {
				$reportToLog .= "Message sent...";
				$dateChunk = date("Ymd-His");
				$reportToLog .= "\t[NMS FSM UNDIP] | [".$dateChunk.".html]";
					
				file_put_contents(APPPATH."/logs/email.log", $reportToLog, FILE_APPEND);
				// file_put_contents(APPPATH."/logs/emails/".$dateChunk.".html", $kontenEmail);
					
				// $data['submitSukses'] = "Password anda berhasil di-reset. <br>Silahkan periksa email Anda untuk melakukan tahap berikutnya";
				print_r( "Terjadi perubahan status Perangkat dan Interface. <br>Silahkan periksa email Anda");
				$this->modelperangkat->drop_perubahan();

			}
			// end if
		}
		// end else if
	}
	// End function

	//Fungsi untuk update berkala rrd database
	public function update_rrd(){
		$data_rrd = $this->modelperangkat->get_rrd_details();
		// $sess = $this->session->userdata('sess');

		// echo getcwd();
		foreach ($data_rrd as $data) {
			if ($data['ver_snmp'] == '1'){
				$in = exec('/usr/local/bin/snmpget -v '.$data['ver_snmp'].' -c '.$data['community'].' -Oqv '.$data['ip_address'].' IF-MIB::ifInOctets.'.$data['ip_addressindex'].'');
		  		$out = exec('/usr/local/bin/snmpget -v '.$data['ver_snmp'].' -c '.$data['community'].' -Oqv '.$data['ip_address'].' IF-MIB::ifOutOctets.'.$data['ip_addressindex'].'');
		  		// echo $in." || ".$out." || ".time()."<br>" ;
		  		$ret = rrd_update("etc/rrdtools/rra/".$data['id_rrd'].".rrd", array("N:".$in.":".$out));
			    if ( $ret == 0 )
				{
				    $err = rrd_error();
				    print_r($now."ERROR occurred:".$err) ;
				}
			}else if ($data['ver_snmp'] == '2'){
				$in = exec('/usr/local/bin/snmpget -v '.$data['ver_snmp'].'c -c '.$data['community'].' -Oqv '.$data['ip_address'].' IF-MIB::ifInOctets.'.$data['ip_addressindex'].'');
		  		$out = exec('/usr/local/bin/snmpget -v '.$data['ver_snmp'].'c -c '.$data['community'].' -Oqv '.$data['ip_address'].' IF-MIB::ifOutOctets.'.$data['ip_addressindex'].'');
		  		// echo $in." || ".$out." || ".time()."<br>" ;
		  		$ret = rrd_update("etc/rrdtools/rra/".$data['id_rrd'].".rrd", array("N:".$in.":".$out));
			    if ( $ret == 0 )
				{
				    $err = rrd_error();
				    print_r($now."ERROR occurred:".$err) ;
				}
			}else{
				$in = exec('/usr/local/bin/snmpget -v'.$data['ver_snmp'].' -l '.$data['type'].' -u '.$data['community'].' -Oqv -a '.$data['authprot'].' -A '.$data['authpass'].'  -x '.$data['encryptprot'].' -X '.$data['encryptpass'].' '.$data['ip_address'].' IF-MIB::ifInOctets.'.$data['ip_addressindex'].'');
		  		$out = exec('/usr/local/bin/snmpget -v'.$data['ver_snmp'].' -l '.$data['type'].' -u '.$data['community'].' -Oqv -a '.$data['authprot'].' -A '.$data['authpass'].'  -x '.$data['encryptprot'].' -X '.$data['encryptpass'].' '.$data['ip_address'].' IF-MIB::ifOutOctets.'.$data['ip_addressindex'].'');
		  		// echo $in." || ".$out." || ".time()."<br>" ;
		  		$ret = rrd_update("etc/rrdtools/rra/".$data['id_rrd'].".rrd", array("N:".$in.":".$out));
			    if ( $ret == 0 )
				{
				    $err = rrd_error();
				    print_r($now."ERROR occurred:".$err) ;
				}
			}






			// $in = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$data['ip_address'].' IF-MIB::ifInOctets.'.$data['ip_addressindex'].'');
	  // 		$out = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$data['ip_address'].' IF-MIB::ifOutOctets.'.$data['ip_addressindex'].'');
	  // 		// echo $in." || ".$out." || ".time()."<br>" ;
	  // 		$ret = rrd_update("etc/rrdtools/rra/".$data['id_rrd'].".rrd", array("N:".$in.":".$out));
		 //    if ( $ret == 0 )
			// {
			//     $err = rrd_error();
			//     print_r($now."ERROR occurred:".$err) ;
			// }
			print_r($in ." || ". $out. "<br>");
		}
	}

	//Fungsi ajax auto refresh
	public function uptime(){
		$sess = $this->session->userdata('sess');
		if ($sess['os']=="mikrotik") {
			if ($sess['ver_snmp'] == '1'){
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v '.$sess['ver_snmp'].' -c '.$sess['comm'].' -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
					'usedmem' => snmpget($sess['ip'], $sess['comm'], ".1.3.6.1.2.1.25.2.3.1.6.65536"),
					'cpuload' => snmpget($sess['ip'], $sess['comm'], ".1.3.6.1.2.1.25.3.3.1.2.1")
				);
			}else if ($sess['ver_snmp'] == '2'){
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v '.$sess['ver_snmp'].'c -c '.$sess['comm'].' -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
					'usedmem' => snmp2_get($sess['ip'], $sess['comm'], ".1.3.6.1.2.1.25.2.3.1.6.65536"),
					'cpuload' => snmp2_get($sess['ip'], $sess['comm'], ".1.3.6.1.2.1.25.3.3.1.2.1")
				);
			}else{
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v3 -l '.$sess['type'].' -u '.$sess['comm'].' -Oqv -a '.$sess['authprot'].' -A '.$sess['authpass'].'  -x '.$sess['encryptprot'].' -X '.$sess['encryptpass'].' '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
					'usedmem' => snmp3_get($sess['ip'], $sess['comm'], $sess['type'], $sess['authprot'], $sess['authpass'], $sess['encryptprot'], $sess['encryptpass'],".1.3.6.1.2.1.25.2.3.1.6.65536"),
					'cpuload' => snmp3_get($sess['ip'], $sess['comm'],$sess['type'], $sess['authprot'], $sess['authpass'], $sess['encryptprot'], $sess['encryptpass'], ".1.3.6.1.2.1.25.3.3.1.2.1")
				);
			}
			// $data = array(
			// 	//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
			// 	// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
			// 	'uptime' => exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
			// 	'usedmem' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.25.2.3.1.6.65536"),
			// 	'cpuload' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.25.3.3.1.2.1")
			// );
		}else{
			if ($sess['ver_snmp'] == '1'){
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v '.$sess['ver_snmp'].' -c '.$sess['comm'].' -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
					'usedmem' => snmpget($sess['ip'], $sess['comm'], ".1.3.6.1.4.1.2021.4.6.0"),
					'cpuload' => snmpget($sess['ip'], $sess['comm'], ".1.3.6.1.4.1.2021.11.9.0")
				);
			}else if ($sess['ver_snmp'] == '2'){
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v '.$sess['ver_snmp'].'c -c '.$sess['comm'].' -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
					'usedmem' => snmp2_get($sess['ip'], $sess['comm'], ".1.3.6.1.4.1.2021.4.6.0"),
					'cpuload' => snmp2_get($sess['ip'], $sess['comm'], ".1.3.6.1.4.1.2021.11.9.0")
				);
			}else{
				$data = array(
					//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
					// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
					'uptime' => exec('/usr/local/bin/snmpget -v3 -l '.$sess['type'].' -u '.$sess['comm'].' -a '.$sess['authprot'].' -A '.$sess['authpass'].'  -x '.$sess['encryptprot'].' -X '.$sess['encryptpass'].' '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),

					'usedmem' => snmp3_get($sess['ip'], $sess['comm'], $sess['type'], $sess['authprot'], $sess['authpass'], $sess['encryptprot'], $sess['encryptpass'], ".1.3.6.1.4.1.2021.4.6.0"),

					'cpuload' => snmp3_get($sess['ip'], $sess['comm'],$sess['type'], $sess['authprot'], $sess['authpass'], $sess['encryptprot'], $sess['encryptpass'], ".1.3.6.1.4.1.2021.11.9.0")
				);
			}
			// $data = array(
			// 	//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
			// 	// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
			// 	'uptime' => exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
			// 	'usedmem' => snmpget($sess['ip'], "public", ".1.3.6.1.4.1.2021.4.6.0"),
			// 	'cpuload' => snmpget($sess['ip'], "public", ".1.3.6.1.4.1.2021.11.9.0")
			// );
		}
		
		echo json_encode($data);
	}


	public function sinkron_ip(){
		$this->load->library('fungsiku');

		$this->modelsquid->sinkron_ip();
	}
	//End Fungsi Ajax auto refresh
	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */