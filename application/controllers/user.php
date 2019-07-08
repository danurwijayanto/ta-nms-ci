
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
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
				redirect(base_url('login_admin'));
			}else{
				$this->session_data = $this->session->userdata('logged_in');
				$this->data_sesi = $this->auth->read_user_information($this->session_data);
			}
	}

	public function data_user(){
		$data = array(
				'title'=>'Network Management System UPPTI FSM UNDIP',
				'isi' =>'admin/isi/data_user',
				'user' => $this->auth->get_all_user(),
				'session' => $this->data_sesi
		);
		$this->load->view('admin/wrapper', $data);
	}

	public function tambah_user(){
		$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password')),
				'role' => $this->input->post('role')
		);

		$result = $this->auth->tambah_user($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		redirect('welcome/data_user', 'refresh');
	}

	public function hapus_user(){
		$data = $_GET['id'];
		$result=$this->auth->hapus_user($data);
		echo "<script type='text/javascript'>alert('".$result."')</script>";
		#redirect($this->agent->referrer(), 'refresh');
		redirect('welcome/data_user', 'refresh');
	}

	public function get_user(){
		$data = $_GET['id'];
		$result=$this->auth->get_user($data);
		echo json_encode($result);
	}
	
	public function simpan_edit_user(){
		$data = array(
				'id' => $_GET['id'],
				'username' => $_POST['username'],
				'email' => $_POST['email'],
				'password' => md5($_POST['password']),
				'role' => $_POST['role'],
			);
		$sess_array = array(
			'email' => $data['email']
		);
		// Add user data in session
		$this->session->set_userdata('logged_in', $sess_array);
		$result=$this->auth->simpan_edit_user($data);
	}

	public function simpan_profile(){
		$data = array(
				'id' => $_POST['id2'],
				'username' => $_POST['username'],
				'email' => $_POST['email'],
				'password' => md5($_POST['password']),
				'role' => $_POST['role'],
			);
		$result=$this->auth->simpan_edit_user($data);
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