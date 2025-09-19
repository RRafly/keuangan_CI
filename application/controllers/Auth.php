<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
    }

    // Halaman login
    public function index()
    {

        if ($this->input->post("email")) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Cek apakah login berhasil
            $user = $this->Auth_model->check_login($email, $password);

            if ($user) {
                // Set session jika login berhasil
                $this->session->set_userdata([
                    'logged_in' => TRUE,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'password_hash' => $user->password,
                    'role' => $user->role
                ]);

                // Redirect berdasarkan role user (admin atau user biasa)
                if ($user->role == 'admin') {
                    redirect('admin');
                } else {
                    redirect('keuangan');
                }
            } else {
                // Jika login gagal, tampilkan pesan error
                $this->session->set_flashdata('login_error', 'Email atau password salah.');
                redirect('auth');  // Kembali ke halaman login
            }
        }

        // update session every action, get logged out if invalid
        $user = $this->Auth_model->check_login_Hashed_Password(
            $this->session->userdata('user_email'),
            $this->session->userdata('password_hash')
        );
        
        if ($user) {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'password_hash' => $user->password,
                'role' => $user->role
            ]);
        
            if ($user->role == 'admin') {
                redirect('admin');
            } else {
                redirect('keuangan');
            }
        }
       
        // Session tidak valid, paksa logout
        $this->session->sess_destroy();

        // Tampilkan halaman login
        $this->load->view('login/index');
    }

    // Fungsi untuk logout
    public function logout()
    {
        // Hapus session
        $this->session->sess_destroy();
        redirect('auth');  // Redirect ke halaman login
    }

    // Halaman register
    public function register()
    {
        // Cek apakah user sudah login
        if ($this->session->userdata('logged_in')) {
            redirect('keuangan');  // Jika sudah login, redirect ke halaman utama
        }

        // Validasi form inputan
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan halaman register
            $this->load->view('register/index');
        } else {
            // Ambil data dari form dan simpan ke dalam array
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // Hash password
                'role' => 'user'
            ];

            // Mendaftarkan user baru
            if ($this->Auth_model->register_user($data)) {
                $this->session->set_flashdata('register_success', 'Pendaftaran berhasil! Silakan login.');
                redirect('auth');  // Setelah berhasil, redirect ke halaman login
            } else {
                $this->session->set_flashdata('register_error', 'Pendaftaran gagal. Coba lagi.');
                $this->load->view("register/index"); // Jika gagal, tetap di halaman register
            }
        }
    }

    public function update_user()
    {
        $id = $this->input->post('id');

        $data = [];
        if ($this->session->userdata('role') == 'admin') {
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            ];
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
            ];
        }


        // Kalau password diisi, reset password
        $password = $this->input->post('password');

        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth');
            return;
        }

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // supaya tidak eror duplicate email
        if ($this->Auth_model->check_email_exists($data['email'])) {
            unset($data['email']);
        }

        $this->Auth_model->update_user($id, $data); 
        $this->session->set_flashdata('success', 'User berhasil diperbarui');
        redirect('auth');
    }
    
    public function hapus_user($id) {
        $this->Auth_model->delete_user($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus');
        redirect('auth');
    }
}
