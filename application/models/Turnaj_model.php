<?php
/**
 * Created by PhpStorm.
 * User: Juraj
 * Date: 7. 5. 2018
 * Time: 14:03
 */
class Turnaj_model extends CI_Model {

	public function __construct()
	{

	}
	// vrati zoznam teplot
	function getRows($idTurnaj= "") {
		if(!empty($idTurnaj)){
			$this->db->select('idTurnaj, Názov_turnaju, Start_turnaja, Mesto_idMesto')
				->join('mesto','Turnaj.Mesto_idMesto = idMesto');
			$query = $this->db->get_where('Turnaj', array('Turnaj.idTurnaj' => $idTurnaj));
			return $query->row_array();
		}else{
			$this->db->select('idTurnaj, Názov_turnaju, Start_turnaja, Mesto_idMesto')
				->join('mesto','Turnaj.Mesto_idMesto = idMesto');
			$query = $this->db->get('Turnaj');
			return $query->result_array();
		}
	}

	// vlozenie zaznamu
	public function insert($data = array()) {
		$insert = $this->db->insert('Turnaj', $data);
		if($insert){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	// aktualizacia zaznamu
	public function update($data, $id) {
		if(!empty($data) && !empty($id)){
			$update = $this->db->update('Turnaj', $data, array('id'=>$id));
			return $update?true:false;
		}else{
			return false;
		}
	}

	// odstranenie zaznamu
	public function delete($id){
		$delete = $this->db->delete('Turnaj',array('id'=>$id));
		return $delete?true:false;
	}
	//  naplnenie selectu z tabulky users
	public function get_users_dropdown($idTurnaj = ""){
		$this->db->order_by('Start_turnaja')
			->select('idTurnaj, Názov_turnaju, Start_turnaja, Mesto_idMesto')
			->from('Turnaj');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$dropdowns = $query->result();
			foreach ($dropdowns as $dropdown)
			{
				$dropdownlist[$dropdown->id] = $dropdown->fullname;
			}
			$dropdownlist[''] = 'Select a user ... ';
			return $dropdownlist;
		}
	}
	// strankovanie tabulky na temperatures/index_pagination
	public function fetch_data($limit,$start) {
		$this->db->limit($limit,$start);
		$query = $this->db->get("Turnaj");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	// pocet vsetky zaznamov pre strankovanie
	public function record_count (){
		return $this->db->count_all("Turnaj");
	}
	//ukazka group by pre tabulku, vystup je objekt
	public function record_count_per_user() {
		$this->db->select('Názov_turnaju,Start_turnaja,Mesto_idMesto');
		$this->db->from('Turnaj');
		$this->db->join('Mesto','Turnaj.Mesto_idMesto = idMesto');
		$this->db->group_by('Turnaj.mesto');
		return $this->db->get();
	}
	//ukazka group_by pre tabulku a graf, vystup je pole
	public function record_count_per_user_array() {
		$this->db->select('Názov_turnaju,Start_turnaja,Mesto_idMesto');
		$this->db->from('Turnaj');
		$this->db->join('Mesto','Turnaj.Mesto_idMesto = idMesto');
		$this->db->group_by('Turnaj.mesto');
		$query = $this->db->get();
		return $query->result_array();
	}
}
