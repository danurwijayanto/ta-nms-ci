
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Controlpengguna extends CI_Controller {
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

	public function data_user(){
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/viewpengguna',
				'user' => $this->modelpengguna->get_all_user(),
				'session' => $this->data_sesi
		);
		$this->load->view('admin/wrapper', $data);
	}

	public function tambah_user(){
		// $notif = $this->input->post('notif');
		if ($this->input->post('notif') == 'on') {
			$notif = 1;
		}else{
			$notif = 0;
		}
		$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password')),
				'role' => $this->input->post('role'),
				'notif' => $notif
		);
		// print_r($notif);
		$result = $this->modelpengguna->tambah_user($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		redirect('controlpengguna/data_user', 'refresh');
	}

	public function hapus_user(){
		$data = $_GET['id'];
		$result=$this->modelpengguna->hapus_user($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		#redirect($this->agent->referrer(), 'refresh');
		redirect('controlpengguna/data_user', 'refresh');
	}

	public function get_user(){
		$data = $_GET['id'];
		$result=$this->modelpengguna->get_user($data);
		echo json_encode($result);
	}
	
	public function simpan_edit_user(){
		// $notif = $this->input->post('notif');
		// if ($_POST['notif'] == true) {
		// 	$notif = 1;
		// }else{
		// 	$notif = 0;
		// }
		$data = array(
				'id' => $_GET['id'],
				'username' => $_POST['username'],
				'email' => $_POST['email'],
				'password' => md5($_POST['password']),
				'role' => $_POST['role'],
				'notif' => $_POST['notif'],
			);
		$sess_array = array(
			'email' => $data['email']
		);
		// Add user data in session
		$this->session->set_userdata('logged_in', $sess_array);
		$result=$this->modelpengguna->simpan_edit_user($data);
		// print_r($data['notif']);
		// echo json_encode($notif);
	}

	public function simpan_profile(){
		$data = array(
				'id' => $_POST['id2'],
				'username' => $_POST['username'],
				'email' => $_POST['email'],
				'password' => md5($_POST['password']),
				'role' => $_POST['role'],
			);
		$result=$this->modelpengguna->simpan_edit_user($data);
		$sess_array = array(
			'email' => $_POST['email']
		);
		// Add user data in session
		$this->session->set_userdata('logged_in', $sess_array);
		redirect($this->agent->referrer());

	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */