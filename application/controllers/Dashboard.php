<?php

class Dashboard extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'masyarakat';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar');
        $this->load->view('admin/dashboard');
        $this->load->view('templates/footer');
    }
}
