<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Alternatif extends CI_Controller {
    
        public function __construct()
        {
            parent::__construct();
            $this->load->library('pagination');
            $this->load->library('form_validation');
            $this->load->model('Alternatif_model');

            if ($this->session->userdata('id_user_level') != "1") {
            ?>
				<script type="text/javascript">
                    alert('Anda tidak berhak mengakses halaman ini!');
                    window.location='<?php echo base_url("Login/home"); ?>'
                </script>
            <?php
			}
        }

        public function index()
        {
            $selected_gelombang = $this->input->get('gelombang'); // Ambil nilai filter Gelombang
        
            $data = [
                'page' => "Alternatif",
                'selected_gelombang' => $selected_gelombang, // Untuk menyimpan nilai filter yang dipilih
                'list' => $this->Alternatif_model->tampilByGelombang($selected_gelombang), // Gunakan model untuk mengambil data alternatif berdasarkan Gelombang
            ];
        
            $this->load->view('alternatif/index', $data);
        }
        
        
        //menampilkan view create
        public function create()
        {
            $data['page'] = "Alternatif";
            $this->load->view('alternatif/create',$data);
        }

        //menambahkan data ke database
        public function store()
        {
                $data = [
                    'nisn' => $this->input->post('nisn'),
                    'nama' => $this->input->post('nama'),
                    'gelombang' => $this->input->post('gelombang')
                ];
                
                $this->form_validation->set_rules('nisn', 'NISN', 'required|is_unique[alternatif.nisn]');               
                $this->form_validation->set_rules('nama', 'Nama', 'required');               
                $this->form_validation->set_rules('gelombang', 'Gelombang', 'required');     
    
                if ($this->form_validation->run() != false) {
                    $result = $this->Alternatif_model->insert($data);
                    if ($result) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
						redirect('alternatif');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan! NISN telah digunakan!</div>');
                    redirect('alternatif/create');
                    
                }
            

        }

        public function edit($id_alternatif)
        {
            $alternatif = $this->Alternatif_model->show($id_alternatif);
            $data = [
                'page' => "Alternatif",
				'alternatif' => $alternatif
            ];
            $this->load->view('alternatif/edit', $data);
        }
    
        public function update($id_alternatif)
        {
            $id_alternatif = $this->input->post('id_alternatif');
            $data = array(
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'gelombang' => $this->input->post('gelombang')
            );

            $this->Alternatif_model->update($id_alternatif, $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
			redirect('alternatif');
        }
    
        public function destroy($id_alternatif)
        {
            $this->Alternatif_model->delete($id_alternatif);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
			redirect('alternatif');
        }
    
    }
    
    