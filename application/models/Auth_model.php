<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

      // Fungsi untuk cek user dan password di database
    public function check_login($email, $password) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            
            // Cek password
            if (password_verify($password, $user->password)) {
                return $user; // Kembalikan data user jika password benar
            }
        }
        
        return NULL; // Jika user atau password salah
    }

    function check_login_Hashed_Password($email, $passwordHash) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            
            // Cek password
            if ($passwordHash == $user->password) {
                return $user;
            }
        }
    }

    // Fungsi untuk mendaftarkan user baru
    public function register_user($data) {
        return $this->db->insert('users', $data); // Menyimpan data ke tabel users
    }

    function update_user($id, $user) {
        $this->db->where('id', $id);
        $this->db->update('users', $user);
        echo  json_encode($user);
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }

    public function check_email_exists($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
    
        return $query->num_rows() > 0;
    }
}
