<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masyarakat extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_masyarakat');
    }


    public function index()
    {

        //jika variabel session login_status == OK maka tampilkan laporan pengaduan
        if ($this->session->userdata('login_status') == 'ok') {
            $this->tampilAduan();
        }
        //jika tidak munculkan login
        else {
            $this->load->view('masyarakat/header');
            $this->load->view('masyarakat/login');
            $this->load->view('masyarakat/footer');
        }
    }

    public function registrasi()
    {
        $this->load->view('masyarakat/header');
        $this->load->view('masyarakat/registrasi');
        $this->load->view('masyarakat/footer');
    }

    public function registrasi_user()
    {
        $this->validasi_form();

        if ($this->form_validation->run() == FALSE) {
            $this->registrasi();
        } else {

            $pass = md5($_POST['password']);
            $data = array(
                'nik' => $_POST['nik'],
                'nama' => $_POST['nama'],
                'username' => $_POST['username'],
                'password' => $pass,
                'telp' => $_POST['telepon']
            );
            if ($this->M_masyarakat->tambahMasyarakat($data)) {
                $this->index();
            }
        }
    }

    public function validasi_form()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');
    }

    public function validasi_form_login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
    }

    public function login()
    {
        $this->validasi_form_login();

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $pass = md5($_POST['password']);

            $data = array(
                'username' => $_POST['username'],
                'password' => $pass
            );

            $data = $this->M_masyarakat->login($data);

            if (count($data) > 0) {
                $this->session->set_userdata('login_status', 'ok');
                $this->session->set_userdata('nik', $data[0]['nik']);
                $this->session->set_userdata('nama', $data[0]['nama']);

                $this->index();
            } else {
                $this->index();
            }
        }
    }

    public function logout()
    {
        unset(
            $_SESSION['login_status'],
            $_SESSION['nik'],
            $_SESSION['nama']
        );

        $this->index();
    }

    public function form_aduan()
    {
        if ($this->session->userdata('login_status') == 'ok') {
            $this->load->view('masyarakat/header');
            $this->load->view('masyarakat/form_aduan');
            $this->load->view('masyarakat/footer');
        } else {
            $this->index();
        }
    }

    public function simpan_aduan()
    {

        $this->form_validation->set_rules('isilaporan', 'Isi Laporan', 'required');


        if ($this->form_validation->run() == FALSE) {
            $this->form_aduan();
        } else {

            $config['upload_path'] = './img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('foto')) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('masyarakat/header');
                $this->load->view('masyarakat/form_aduan', $error);
                $this->load->view('masyarakat/footer');
            } else {
                $data = $this->upload->data();
                $filename = $data['file_name'];

                $data = array(
                    'tgl_pengaduan' => date('Y-m-d'),
                    'nik'                   => $_POST['nik'],
                    'isi_laporan'       => $_POST['isilaporan'],
                    'foto'                 => $filename
                );

                if ($this->M_masyarakat->tambahAduan($data)) {

                    redirect('masyarakat/tampilAduan', 'refresh');
                } else {
                    $error = array('error' => 'Gagal Simpan Data');
                    $this->load->view('masyarakat/header');
                    $this->load->view('masyarakat/form_aduan', $error);
                    $this->load->view('masyarakat/footer');
                }
            }
        }
    }

    public function tampilAduan()
    {
        $data['aduan'] = $this->M_masyarakat->tampilAduan();
        $this->load->view('masyarakat/header');
        $this->load->view('masyarakat/aduan', $data);
        $this->load->view('masyarakat/footer');
    }
}
