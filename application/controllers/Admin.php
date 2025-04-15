<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth');
        }

        $this->load->model('Auth_model');
        $this->load->model('Keuangan_model');
        $this->load->library('form_validation');
    }

    // Admin dashboard
    public function index() {
        $data['users'] = $this->Keuangan_model->get_all_users();
        
        foreach ($data['users'] as &$user) {
            $user->transactions = $this->Keuangan_model->get_all_transaksi($user->id);
            $user->total_income = $this->Keuangan_model->get_total_income($user->id);
            $user->total_expenses = $this->Keuangan_model->get_total_expenses($user->id);
            $user->total_balance = $user->total_income - $user->total_expenses;
        }

        $this->load->view('admin/index', $data);
    }

    public function update_user() {
        $id = $this->input->post('id');
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role')
        ];
    
        // Kalau password diisi, reset password
        $password = $this->input->post('password');

        
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin');
            return;
        }

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
    
        $this->Auth_model->update_user($id, $data);
        $this->session->set_flashdata('success', 'User berhasil diperbarui');
        redirect('admin');
    }

    public function tambah_transaksi() {
        $data = [
            'user_id' => $this->input->post('user_id'),
            'amount' => $this->input->post('amount'),
            'description' => $this->input->post('description'),
            'date' => $this->input->post('date'),
            'type' => $this->input->post('type')
        ];

        $this->Keuangan_model->add_transaksi($data);
        $this->session->set_flashdata('success', 'Transaksi berhasil ditambahkan');
        redirect('admin');
    }

    public function update_transaksi() {
        $id = $this->input->post("id");
        $data = [
            'amount' => $this->input->post('amount'),
            'description' => $this->input->post('description'),
            'date' => $this->input->post('date'),
            'type' => $this->input->post('type')
        ];

        $this->Keuangan_model->update_transaksi($id, $data);
        $this->session->set_flashdata('success', 'Transaksi berhasil diperbarui');
        redirect('admin');
    }

    // Delete transaction (for admin)
    public function hapus_transaksi($id) {
        $this->Keuangan_model->delete_transaksi($id);
        $this->session->set_flashdata('success', 'Transaksi berhasil dihapus');
        redirect('admin');
    }

    public function tambah_user() {
        $data = [ 
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
        ];

        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin');
            return;
        }

        $this->session->set_flashdata('success', 'User baru berhasil ditambahkan');
        $this->Auth_model->register_user($data);
        redirect('admin');
    }

}