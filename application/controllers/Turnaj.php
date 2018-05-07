<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Turnaj extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('Turnaj_model');

		$this->load->library('pagination');
	}

	public function index(){
		$data = array();

		//ziskanie sprav zo session
		if($this->session->userdata('success_msg')){
			$data['success_msg'] = $this->session->userdata('success_msg');
			$this->session->unset_userdata('success_msg');
		}
		if($this->session->userdata('error_msg')){
			$data['error_msg'] = $this->session->userdata('error_msg');
			$this->session->unset_userdata('error_msg');
		}

		$data['temperatures'] = $this->Turnaj_model->getRows();
		$data['title'] = 'Temperature List';

		//nahratie zoznamu teplot
		$this->load->view('templates/header', $data);
		$this->load->view('temperatures/index', $data);
		$this->load->view('templates/footer');
	}

	public function index_pagination(){
		$data = array();

		//ziskanie sprav zo session
		if($this->session->userdata('success_msg')){
			$data['success_msg'] = $this->session->userdata('success_msg');
			$this->session->unset_userdata('success_msg');
		}
		if($this->session->userdata('error_msg')){
			$data['error_msg'] = $this->session->userdata('error_msg');
			$this->session->unset_userdata('error_msg');
		}

		$config = array();
		$config["base_url"] = base_url() . "index.php/temperatures/index_pagination";
		$config["total_rows"] = $this->Turnaj_model->record_count();
		$config["per_page"] = 5;
		$config["uri_segment"] = 3;
		//  $config['use_page_numbers'] = TRUE;
		//$config['num_links'] = $this->Temperatures_model->record_count();
		$config['cur_tag_open'] = '&nbsp;<a class="page-link">';
		$config['cur_tag_close'] = '</a>';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';

		$this->pagination->initialize($config);
		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
		}
		else{
			$page = 0;
		}
		$data["temperatures"] = $this->Turnaj_model->fetch_data($config["per_page"], $page);
		$str_links = $this->pagination->create_links();
		$data["links"] = explode('&nbsp;',$str_links );

		$data['records_per_user'] = $this->Turnaj_model->record_count_per_user();
		$data['json_records_per_user'] = json_encode($this->Turnaj_model->record_count_per_user_array());

		// $data['temperatures'] = $this->Temperatures_model->getRows();
		$data['title'] = 'Temperature List';

		//nahratie zoznamu teplot
		$this->load->view('templates/header', $data);
		$this->load->view('temperatures/index_pagination', $data);
		$this->load->view('templates/footer');
	}

	public function json_records_per_user() {
		$data = $this->Turnaj_model->record_count_per_user_array();

		//         //data to json
		// $responce = nil;
		$responce->cols[] = array(
			"id" => "",
			"label" => "User",
			"pattern" => "",
			"type" => "string"
		);
		$responce->cols[] = array(
			"id" => "",
			"label" => "Counts",
			"pattern" => "",
			"type" => "number"
		);
		foreach($data as $row)
		{
			$responce->rows[]["c"] = array(
				array(
					"v" => $row['user'],
					"f" => null
				) ,
				array(
					"v" => (int)$row['counts'],
					"f" => null
				)
			);
		}

		echo json_encode($responce);
	}


	// Zobrazenie detailu o teplote
	public function view($idTurnaj){
		$data = array();

		//kontrola, ci bolo zaslane id riadka
		if(!empty($idTurnaj)){
			$data['temperatures'] = $this->Turnaj_model->getRows($idTurnaj);
			$data['title'] = $data['temperatures']['measurement_date'];

			//nahratie detailu zaznamu
			$this->load->view('templates/header', $data);
			$this->load->view('temperatures/view', $data);
			$this->load->view('templates/footer');
		}else{
			redirect('/temperatures');
		}
	}

	// pridanie zaznamu
	public function add(){
		$data = array();
		$postData = array();

		//zistenie, ci bola zaslana poziadavka na pridanie zazanmu
		if($this->input->post('postSubmit')){
			//definicia pravidiel validacie
			$this->form_validation->set_rules('measurement_date', 'date of measurement', 'required');
			$this->form_validation->set_rules('temperature', 'temperature', 'required');
			$this->form_validation->set_rules('sky', 'sky value', 'required');
			$this->form_validation->set_rules('user', 'user id', 'required');

			//priprava dat pre vlozenie
			$postData = array(
				'measurement_date' => $this->input->post('measurement_date'),
				'temperature' => $this->input->post('temperature'),
				'sky' => $this->input->post('sky'),
				'user' => $this->input->post('user'),
				'description' => $this->input->post('description'),
			);

			//validacia zaslanych dat
			if($this->form_validation->run() == true){
				//vlozenie dat
				$insert = $this->Turnaj_model->insert($postData);

				if($insert){
					$this->session->set_userdata('success_msg', 'Temperature has been added successfully.');
					redirect('/temperatures');
				}else{
					$data['error_msg'] = 'Some problems occurred, please try again.';
				}
			}
		}
		$data['users'] = $this->Turnaj_model->get_users_dropdown();
		$data['users_selected'] = '';
		$data['post'] = $postData;
		$data['title'] = 'Create Temperature';
		$data['action'] = 'Add';

		//zobrazenie formulara pre vlozenie a editaciu dat
		$this->load->view('templates/header', $data);
		$this->load->view('temperatures/add-edit', $data);
		$this->load->view('templates/footer');
	}

	// aktualizacia dat
	public function edit($idTurnaj){
		$data = array();
		//ziskanie dat z tabulky
		$postData = $this->Turnaj_model->getRows($idTurnaj);

		//zistenie, ci bola zaslana poziadavka na aktualizaciu
		if($this->input->post('postSubmit')){
			//definicia pravidiel validacie
			$this->form_validation->set_rules('measurement_date', 'date of measurement', 'required');
			$this->form_validation->set_rules('temperature', 'temperature value', 'required');
			$this->form_validation->set_rules('sky', 'sky value', 'required');
			$this->form_validation->set_rules('user', 'user id', 'required');

			// priprava dat pre aktualizaciu
			$postData = array(
				'measurement_date' => $this->input->post('measurement_date'),
				'turnaj' => $this->input->post('turnaj'),
				'sky' => $this->input->post('sky'),
				'user' => $this->input->post('user'),
				'description' => $this->input->post('description'),
			);

			//validacia zaslanych dat
			if($this->form_validation->run() == true){
				//aktualizacia dat
				$update = $this->Turnaj_model->update($postData, $idTurnaj);

				if($update){
					$this->session->set_userdata('success_msg', 'Temperature has been updated successfully.');
					redirect('/temperatures');
				}else{
					$data['error_msg'] = 'Some problems occurred, please try again.';
				}
			}
		}

		$data['users'] = $this->Turnaj_model->get_users_dropdown();
		$data['users_selected'] = $postData['user'];
		$data['post'] = $postData;
		$data['title'] = 'Update Turnaje';
		$data['action'] = 'Edit';

		//zobrazenie formulara pre vlozenie a editaciu dat
		$this->load->view('templates/header', $data);
		$this->load->view('temperatures/add-edit', $data);
		$this->load->view('templates/footer');
	}

	// odstranenie dat
	public function delete($idTurnaj){
		//overenie, ci id nie je prazdne
		if($idTurnaj){
			//odstranenie zaznamu
			$delete = $this->Turnaj_model->delete($idTurnaj);

			if($delete){
				$this->session->set_userdata('success_msg', 'Temperature has been removed successfully.');
			}else{
				$this->session->set_userdata('error_msg', 'Some problems occurred, please try again.');
			}
		}

		redirect('/turnament');
	}
}
