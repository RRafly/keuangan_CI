<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private $table_transaksi = 'transactions';
    private $table_user = 'users';

    // Fungsi untuk mengambil semua transaksi berdasarkan user
    public function get_all_transaksi($user_id) {
        // echo $user_id;
        $this->db->where('user_id', $user_id);  // Filter berdasarkan user_id
        
        $query = $this->db->get($this->table_transaksi);

        return $query->result();
    }

    public function get_total_income($user_id) {
        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', 'masuk');
        $query = $this->db->get($this->table_transaksi);
        return $query->row()->amount ?: 0;
    }
    
    public function get_total_expenses($user_id) {
        $this->db->select_sum('amount');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', 'keluar');
        $query = $this->db->get($this->table_transaksi);
        return $query->row()->amount ?: 0;
    }

    // Fungsi untuk menambahkan transaksi baru
    public function add_transaksi($data) {
        return $this->db->insert($this->table_transaksi, $data);
    }

    // Fungsi untuk mendapatkan transaksi berdasarkan ID
    public function get_transaksi_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_transaksi);
        return $query->row();
    }

    // Fungsi untuk mengupdate transaksi
    public function update_transaksi($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_transaksi, $data);
    }

    public function delete_transaksi($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_transaksi);
    }

    public function get_all_users() {
        $query = $this->db->get($this->table_user);
        return $query->result();
    }

    // Fungsi untuk mendapatkan data user berdasarkan ID
    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_user);
        return $query->row();
    }

    // Fungsi untuk menambahkan pengguna baru
    public function add_user($data) {
        return $this->db->insert($this->table_user, $data);
    }

    // Fungsi untuk mengupdate data pengguna
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_user, $data);
    }

    // Fungsi untuk menghapus pengguna
    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_user);
    }
}
