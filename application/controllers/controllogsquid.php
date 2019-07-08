
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Controllogsquid extends CI_Controller {
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
				redirect(base_url('controllogin'));
			}else{
				$this->session_data = $this->session->userdata('logged_in');
				$this->data_sesi = $this->modelpengguna->read_user_information($this->session_data);
			}
	}

	public function log_squid(){	
		$this->load->library('fungsiku');

		$data=array(
			'title'=>'Network Management System UPPTI FSM UNDIP',
			'isi' =>'admin/isi/viewlogsquid',
			'log_squid' => $this->modelsquid->get_log(),
			'session' => $this->data_sesi

		);
		$this->load->view('admin/wrapper', $data);
	}

	public function popular_site(){
		$data=array(
			'title'=>'Network Management System UPPTI FSM UNDIP',
			'isi' =>'admin/isi/viewsitusterpopuler',
			'pop_site' => $this->modelsquid->popular_site(),
			'stats' => $this->modelsquid->get_namaif() ,
			'session' => $this->data_sesi
			// 'stats' => $this->modelsquid->count_domaintuj() 
		);
		 $this->load->view('admin/wrapper', $data);
	}

	public function statistik(){
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/viewcarilog',
				'interface' => $this->modelperangkat->get_interface_active(),
				'session' => $this->data_sesi
		);
		$this->load->view('admin/wrapper', $data);
	}

	public function cari_statistik(){
		
		$post_data = array(
				'id_if' => $this->input->post('if'),
				'tawal' => $this->input->post('tanggal_awal'),
				'takhir' => $this->input->post('tanggal_akhir')
		);
		 // print_r($post_data['tawal']);
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/viewcarilog',
				'interface' => $this->modelperangkat->get_interface_active(),
				'statistik' => $this->modelsquid->cari_statistik($post_data),
				'session' => $this->data_sesi
		);
		$this->load->view('admin/wrapper', $data);
		// print_r($data['statistik']);
	}
	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */