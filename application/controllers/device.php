
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device extends CI_Controller {
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

	public function __construct() {
		parent::__construct();			
			if ($this->session->userdata('logged_in')==NULL) {
				redirect(base_url('index.php/login_admin'));
			}else{
				$this->session_data = $this->session->userdata('logged_in');
				$this->data_sesi = $this->auth->read_user_information($this->session_data);
			}
	}

	public function index()
	{
		// $data_sesi = $this->auth->read_user_information($this->session_data);
		$data=array(
			'title'=>'Network Management System UPPTI FSM UNDIP',
			'isi' =>'admin/isi/home',
			// 'session' => $this->data_sesi,
			'session' => $this->auth->read_user_information($this->session_data),
			'top_site' => $this->squid_model->get_namaif(),
			'pop_site' => $this->squid_model->popular_site(),
			'total_user' => $this->auth->get_all_user(),
			'total_device' => $this->snmp_model->get_alldev(),
			'total_if' => $this->snmp_model->get_allif(),
			'bandbrd1' => $this->snmp_model->getbandwith(19),
			'bandbrd2' => $this->snmp_model->getbandwith(20),
			'statperup' => count($this->snmp_model->get_statusperangkat('Up')),
			'ifperup' => count($this->snmp_model->get_statusif('up')),
			'statperdown' => count($this->snmp_model->get_statusperangkat('Down')),
			'ifperdown' => count($this->snmp_model->get_statusif('down')),
		);
		// print_r($data['top_site']);
		$this->load->view('admin/wrapper', $data);
		// print_r($data['bandbrd1']);
	}
	
	public function data_perangkat(){
		$data=array(
			'title'=>'Network Management System UPPTI FSM UNDIP',
			'isi' =>'admin/isi/data_perangkat',
			'data_perangkat' => $this->snmp_model->get_alldev(),
			'session' => $this->data_sesi
		);
		$this->load->view('admin/wrapper', $data);
	}

	public function tambah_perangkat(){
		$data = array(
					'id_perangkat'=> '',
					'nama_perangkat' => $this->input->post('nama_perangkat'),
					'ip_address' => $this->input->post('ip'),
					'lokasi' => $this->input->post('lokasi'),
					'community' => $this->input->post('community'),
					'ver_snmp' => $this->input->post('ver')
				);
		$result=$this->snmp_model->simpan_perangkat($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		redirect('welcome/data_perangkat', 'refresh');
	}

	public function hapus_perangkat(){
		$data = $_GET['id'];
		$result=$this->snmp_model->hapus_perangkat($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		#redirect($this->agent->referrer(), 'refresh');
		redirect('welcome/data_perangkat', 'refresh');
	}

	public function get_perangkat(){
		$data = $_GET['id'];
		$result=$this->snmp_model->get_perangkat($data);
		echo json_encode($result);
	}

	public function simpan_edit_perangkat(){
		$data = array(
				'id' => $_GET['id'],
				'nama_perangkat' => $_POST['nama_perangkat'],
				'ip_address' => $_POST['ip_address'],
				'lokasi' => $_POST['lokasi'],
				'community' => $_POST['community'],
				'os' => $_POST['os'],
			);
		$result=$this->snmp_model->simpan_edit_perangkat($data);
	}

	public function scan_interface(){
		$id = $_GET['id'];
		$sess1 = $this->session->userdata('sess');
		$this->load->library('fungsiku');
		$db = array(
				'id' => $id,
				'id_if' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.2.2.1.1"),
				'nama_if' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.2.2.1.2"),
				'status_if' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.2.2.1.7"),
				// 'status_if' => explode(' ',exec('/usr/local/bin/snmpwalk -v 1 -c public -Oqv '.$sess1['ip'].' IF-MIB::ifAdminStatus')),
				'list_ip' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.4.20.1.1"),
				'ip_index' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.4.20.1.2"),
				'netmask' => snmpwalk($sess1['ip'], "public", ".1.3.6.1.2.1.4.20.1.3"),
			);
		print_r($db['status_if']);
		$status_if = exec('/usr/local/bin/snmpget -v 1 -c public -Oqv '.$sess1['ip'].' IF-MIB::ifAdminStatus');
		$result=$this->snmp_model->simpan_scan_if($db);
		$result1=$this->snmp_model->simpan_scan_ip($db);
		echo "<script type='text/javascript'>alert('Perubahan Berhasil')</script>";
		redirect($this->agent->referrer());

		// print_r($db['netmask']);
	}


	public function detail_perangkat(){
		$id = $_GET['id'];
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/detail_perangkat',
				'detail' => $this->snmp_model->get_perangkat($id),
				'data_id' => $this->snmp_model->get_data_if($id),
				'session' => $this->data_sesi
					
		);
		foreach ($data['detail'] as $det) {
			$ip = $det["ip_address"];
			$os = $det["os"];
		}
		if ($os=="mikrotik"){
			$data['snmp'] = array(
				'totmem' => preg_replace("/[INTEGER:]/","",snmpget($ip, "public", ".1.3.6.1.2.1.25.2.3.1.5.65536"))
			);		
		}else{
			$data['snmp'] = array(
				'totmem' => preg_replace("/[INTEGER:]/","",snmpget($ip, "public", ".1.3.6.1.4.1.2021.4.5.0"))
			);
		}
		
		//Session untuk menyimpan data ip untuk digunakan di ajax
		$session_data = array(
				'ip'=> $ip,
				'os' => $os
		);
		//Mengeset nama session sebagai sess dengan data session_data
		$this->session->set_userdata('sess', $session_data);
		//End Session


		$this->load->view('admin/wrapper', $data);
	}

	public function detail_if(){
		$id = array(
			'id_if' => $this->input->get('id_if'),
			'id_per' => $this->input->get('id_per')
		);				
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/detail_interface',
				'det_if' => $this->snmp_model->get_detail_if($id),
				'session' => $this->data_sesi,
				'cek_rrd' => $this->snmp_model->cek_rrd($id),
				'id' => $id
				#'statistik' => $this->squid_model->cari_statistik($id_if)
		);
		
		// print_r($data['cek_rrd']);
		$this->load->view('admin/wrapper', $data);
		
	}

	/*
		*Fungsi untuk membuat database rrd pertama kali
	*/
	public function create_rrd(){
		$referred_from = $this->session->userdata('referred_from');
		$id = array(
				'id_if' => $this->input->get('id_if'),
				'id_per' => $this->input->get('id_per')
		);
		$nama = $id['id_if']."_".$id['id_per'];

		// Simpan nama database rrd dalam mysql database
		$this->snmp_model->simpan_rrd($id);

		// Membuat rrd database
		$options = array(
			 "--step", "60",            // Use a step-size of 1 minutes
			 "--start", "N",     // this rrd started now
			 "DS:in:COUNTER:600:U:U",
			 "DS:out:COUNTER:600:U:U",
			 // Update RRA setiap 1 menit sekali selama 1 hari
			 "RRA:AVERAGE:0.5:1:1440",
			 // Update RRA setiap 1 jam sekali selama 1 minggu
			 "RRA:AVERAGE:0.5:60:168",
			 // Update RRA setiap 1 hari sekali selama 1 bulan
			 "RRA:AVERAGE:0.5:1440:30",
			 // Update RRA setiap 1 hari sekali selama 1 tahun
			 "RRA:AVERAGE:0.5:1440:365",
		);

		$ret = rrd_create("etc/rrdtools/rra/".$nama.".rrd", $options);

		// 
		// if (! $ret) {
		// 	echo "<script type='text/javascript'>alert('Gagal Membuat Database ".rrd_error()."')</script>";
		// }else{
		// 	echo "<script type='text/javascript'>alert('Sukses Membuat Database ".$nama.".rrd')</script>";
		// }
		// echo getcwd()."<br>";
		redirect($this->agent->referrer());
	}
	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */