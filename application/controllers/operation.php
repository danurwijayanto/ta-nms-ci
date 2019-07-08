
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Operation extends CI_Controller {
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

	public function index(){
		$this->load->view('admin/isi/login_admin');
	}

	public function cek_status(){
		$this->load->library('email');
		$this->load->library('fungsiku');
		

		$semua_perangkat = $this->snmp_model->get_alldev();
		foreach ($semua_perangkat as $a) {
			# code...
			$status_per = $this->fungsiku->ping($a['ip_address']);
			// echo $a['status']." || ".$status."<br>";
			// if ($a['status'] == $status){
			// 	// echo "sama <br>";

			// }else{
			// 	$data = array(
			// 			'id' => $a['id_perangkat'],
			// 			'status_baru' => $status
			// 		);
			// 	// print_r($data['id']."<br>");
			// 	// echo "beda <br>";
			// 	$this->snmp_model->rubah_statperangkat($data);
			// }

			if (($a['status'] == "Up") && ($status_per == "Up")){
				$data_if = $this->snmp_model->cek_interface($a['id_perangkat']);
				if ($data_if != 0){
					foreach ($data_if as $if) {
						// echo $if['interface_index']."<br>";
						$status_if = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.'.$if['interface_index'].'');
						// echo $status_baru. "||" .$if['status']."<br>";

						$data = array(
							'id' => $a['id_perangkat'],
							'status_if_baru' => $status_if,
							'if_index' => $if['interface_index']
						);
						if ($status_if != $if['status']){
							$this->snmp_model->rubah_statperangkat($data, 0);
						}
					}
				}

		
			}else if (($a['status'] == "Up") && ($status_per == "Down")){
				$data = array(
						'id' => $a['id_perangkat'],
						'status_per_baru' => $status_per
					);
				// print_r($data['id']."<br>");
				// echo "beda <br>";
				$this->snmp_model->rubah_statperangkat($data,1);

			}else if (($a['status'] == "Down") && ($status_per == "Up")){
				$data_if = $this->snmp_model->cek_interface($a['id_perangkat']);
				if ($data_if != 0){
					foreach ($data_if as $if) {
						// echo $if['interface_index']."<br>";
						$status_if = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$a['ip_address'].' IF-MIB::ifAdminStatus.'.$if['interface_index'].'');
						// echo $status_baru. "||" .$if['status']."<br>";

						$data = array(
							'id' => $a['id_perangkat'],
							'status_if_baru' => $status_if,
							'status_per_baru' => $status_per,
							'if_index' => $if['interface_index']
						);
						if ($status_if != $if['status']){
							$this->snmp_model->rubah_statperangkat($data, 2);
						}
					}
				}
				// print_r($data['id']."<br>");
				// echo "beda <br>";
				$this->snmp_model->rubah_statperangkat($data, 1);
			}else if (($a['status'] == "Down") && ($status_per == "Down")){
				// print_r("sesudah");
			}


			
		}

		$data_perubahan = $this->snmp_model->cek_perubahan();

		if ($data_perubahan == 0){
			print_r("database_kosong");
		}else{
			$konten = " 	
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




			$this->email->from('mobinity.fx@gmail.com', 'NMS FSM UNDIP');
			$this->email->to('mobinity.fx@gmail.com'); 
			$this->email->subject('Notifikasi Status Perangkat dan Interface ');
			$this->email->message($konten);	


			
			$berhasil = $this->email->send();

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
				$this->snmp_model->drop_perubahan();

			} 
		}
	}

	//Fungsi untuk update berkala rrd database
	public function update_rrd(){
		$data_rrd = $this->snmp_model->get_rrd_details();
		// echo getcwd();
		foreach ($data_rrd as $data) {
			$in = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$data['ip_address'].' IF-MIB::ifInOctets.'.$data['ip_addressindex'].'');
	  		$out = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$data['ip_address'].' IF-MIB::ifOutOctets.'.$data['ip_addressindex'].'');
	  		// echo $in." || ".$out." || ".time()."<br>" ;
	  		$ret = rrd_update("etc/rrdtools/rra/".$data['id_rrd'].".rrd", array("N:".$in.":".$out));
		    if ( $ret == 0 )
			{
			    $err = rrd_error();
			    print_r($now."ERROR occurred:".$err) ;
			}
			// print_r($in);
		}
	}

	//Fungsi ajax auto refresh
	public function uptime(){
		$sess = $this->session->userdata('sess');
		if ($sess['os']=="mikrotik") {
			$data = array(
				//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
				// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
				'uptime' => exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
				'usedmem' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.25.2.3.1.6.65536"),
				'cpuload' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.25.3.3.1.2.1")
			);
		}else{
			$data = array(
				//'nama_perangkat' => snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0"),
				// 'uptime' => snmpget($sess['ip'], "public", ".1.3.6.1.2.1.1.3.0"),
				'uptime' => exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$sess['ip'].' .1.3.6.1.2.1.1.3.0'),
				'usedmem' => snmpget($sess['ip'], "public", ".1.3.6.1.4.1.2021.4.6.0"),
				'cpuload' => snmpget($sess['ip'], "public", ".1.3.6.1.4.1.2021.11.9.0")
			);
		}
		
		echo json_encode($data);
	}


	public function sinkron_ip(){
		$this->load->library('fungsiku');

		$this->squid_model->sinkron_ip();
	}
	//End Fungsi Ajax auto refresh
	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */