<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $this->load->model('Keuangan_model');
    }

    // Halaman utama - daftar transaksi
    public function index() {
        $user_id = $this->session->userdata('user_id');

        $data['transaksi'] = $this->Keuangan_model->get_all_transaksi($user_id);
        $data['total_income'] = $this->Keuangan_model->get_total_income($user_id);
        $data['total_expenses'] = $this->Keuangan_model->get_total_expenses($user_id);
        $data['total_balance'] = $data['total_income'] - $data['total_expenses'];
        $data['user'] = [
            'name' => $this->session->userdata('user_name'),
            'email' => $this->session->userdata('user_email'),
            'role' => $this->session->userdata('role')
        ];

        $this->load->view('user/index', $data);
    }

    // Tambah transaksi
    public function tambah() {
        $data = [
            'user_id' => $this->session->userdata('user_id'),
            'amount' => $this->input->post('amount'),
            'description' => $this->input->post('description'),
            'date' => $this->input->post('date'),
            'type' => $this->input->post('type')
        ];

        $this->Keuangan_model->add_transaksi($data);
        redirect('keuangan');
    }

    // Update transaksi
    public function update() {
        $id = $this->input->post("id");
        $data = [
            'amount' => $this->input->post('amount'),
            'description' => $this->input->post('description'),
            'date' => $this->input->post('date'),
            'type' => $this->input->post('type')
        ];

        $this->Keuangan_model->update_transaksi($id, $data);
        // echo json_encode($data);
        redirect('keuangan');
    }

    // Hapus transaksi
    public function hapus($id) {
        $this->Keuangan_model->delete_transaksi($id);
        redirect('keuangan');
    }
}
