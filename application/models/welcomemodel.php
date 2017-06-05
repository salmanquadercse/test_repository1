<?php
class Welcomemodel extends CI_Model{
	function get_post_list(){
		$query = $this->db->query('select ps.*,psc.title,psc.created_at from posts ps inner join post_category psc on ps.post_category_id=psc.id');
		return $query->row_array();
	}
	function get_post_list_1(){
		/* $this->db->select('p.*,pc.title,pc.created_at');
		$this->db->from('posts as p');
		$this->db->join('post_category as pc','p.post_category_id=pc.id','inner');
		$query = $this->db->get(); */
		$query = $this->db->get('posts');
		return $query->result_array();
	}

}

?>