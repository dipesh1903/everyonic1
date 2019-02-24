<?php
if (! defined ( 'BASEPATH' ))exit ( 'No direct script access allowed' );

class Package_assigner_back extends MX_Controller 
{
	// wGtRkO8VoEyUjS
	function __construct() 
	{
		parent::__construct ();
		$this->load->model ( 'Mdl_package_assigner_back' );
	}
	
	function index() 
	{
		$this->load->module ( 'login' );
		if ($this->login->auth ()) 
		{
			echo 1; // success
		} else {
			echo 0; // logout
		}
	}
	function save() 
	{
		$this->load->library ( 'form_validation' );
		if ($this->input->post('ms_id') || $this->input->post('ds_id') || $this->input->post('rt_id')) 
		{
			$this->form_validation->set_rules ( "ms_id", "Member type", "trim" );
			$this->form_validation->set_rules ( "ds_id", "dis Member type", "trim" );
			$this->form_validation->set_rules ( "rt_id", "ret Member type", "trim" );
		} 
		else 
		{
			$this->form_validation->set_rules ("rt_id","Select Anyone Memeber's", "trim|required" );
		}
		$this->form_validation->set_rules ( "pac_id", "Package Name", "required|trim" );
		
		if ($this->form_validation->run () == TRUE) 
		{
			$val ['pac_id'] = $this->input->post ( 'pac_id' );
// 			if (isset ( $_POST ['admin_id'] ) && $this->input->post ('admin_id'))
// 				$val ['mem_id'] = $this->input->post ( 'admin_id' );
			if (isset ( $_POST ['ms_id']) && $this->input->post('ms_id'))
				$val ['mem_id'] = $this->input->post ( 'ms_id' );
			if (isset ($_POST ['ds_id']) && $this->input->post ('ds_id'))
				$val ['mem_id'] = $this->input->post ('ds_id');
			if (isset ( $_POST ['rt_id'] ) && $this->input->post ('rt_id'))
				$val ['mem_id'] = $this->input->post ( 'rt_id' );
			
			if ($this->input->post( 'mem_typ' ))
				$val ['mem_typ'] = $this->input->post ( 'mem_typ' );
				
			if ($this->input->post ( 'id' )) // update
			{
				$where ['pac_id'] = $this->input->post ( 'id' );
				$where ['com_id'] = $this->session->userdata ( 'com_id' ); // comment when testing with static data
				echo $this->Mdl_package_assigner_back->update_data ( $where, $val );
			} 
			else // add
			{
					$val ['com_id'] = $this->session->userdata ( 'com_id' ); // comment when testing with static data
					if ($val ['mem_id'] == 'a' && $this->input->post ( 'mem_typ' ) == 'ms')
					{
						// getting the service id from post pac id
						$where1 ['com_id'] = $val ['com_id'];
						$where1 ['pac_id'] = $val ['pac_id'];
						$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
						$ser_id = $ser [0]->ser_id;
						
						// getting all operators of that service id
 						//$where2 ['com_id'] = $val ['com_id'];
						$where2 ['ser_id'] = $ser_id;
						$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
						// getting masters details
						$masters = $this->db->select ( 'ms_id' )->where ( 'com_id', $where1 ['com_id'] )->get ( 'master_details' )->result ();
						
						foreach ( $masters as $m )
						{
							foreach ( $opcode as $o )
							{
								$val ['mem_id'] = $m->ms_id;
								$val ['sercode'] = $o->sercode;
								$a = $this->db->where ( $val )->get ( 'package_assigner' );
								
								if ($a->num_rows () > 0)
								{
									$this->db->where ($val );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x= $this->db->affected_rows ( $c );
								}
							}
						}
						echo $x;
					}
					elseif ($val ['mem_id'] == 'b' && $this->input->post ( 'mem_typ' ) == 'ds')
					{
						// getting the service id from post pac id
						$where1 ['com_id'] = $val ['com_id'];
						$where1 ['pac_id'] = $val ['pac_id'];
						$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
						$ser_id = $ser [0]->ser_id;
						
						// getting all operators of that service id
 						//$where2 ['com_id'] = $val ['com_id'];
						$where2 ['ser_id'] = $ser_id;
						$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
						
						// getting masters details
						$masters = $this->db->select ( 'ds_id' )->where ( 'com_id', $where1 ['com_id'] )->get ( 'distributor_details' )->result ();
						
						foreach ( $masters as $m )
						{
							foreach ( $opcode as $o )
							{
								$val ['mem_id'] = $m->ds_id;
								$val ['sercode'] = $o->sercode;
								
								$a = $this->db->where ( $val )->get ( 'package_assigner' );
								if ($a->num_rows () > 0)
								{
									$this->db->where ($val );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
							}
						}
						echo $x;
					}
					elseif ($val ['mem_id'] == 'c' && $this->input->post ( 'mem_typ' ) == 'rt')
					{
						// getting the service id from post pac id
						$where1 ['com_id'] = $val ['com_id'];
						$where1 ['pac_id'] = $val ['pac_id'];
						$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
						$ser_id = $ser [0]->ser_id;
						
						// getting all operators of that service id
 						//$where2 ['com_id'] = $val ['com_id'];
						$where2 ['ser_id'] = $ser_id;
						$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
						
						// getting masters details
						$masters = $this->db->select ( 'rt_id' )->where ( 'com_id', $where1 ['com_id'] )->get ( 'retailer_details' )->result ();
						
						foreach ( $masters as $m )
						{
							foreach ( $opcode as $o )
							{
								$val ['mem_id'] = $m->rt_id;
								$val ['sercode'] = $o->sercode;
								
								$a = $this->db->where ( $val )->get ( 'package_assigner' );
								if ($a->num_rows () > 0)
								{
									$this->db->where ($val );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
							}
						}
						echo $x;
					}
					else
					{
						if ($this->input->post ( 'mem_typ' ) == 'ms' && $this->input->post ( 'ms_id' ))
						{
							// getting the service id from post pac id
							$where1 ['com_id'] = $val ['com_id'];
							$where1 ['pac_id'] = $val ['pac_id'];
							$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
							$ser_id = $ser [0]->ser_id;
							
							// getting all operators of that service id
 							//$where2 ['com_id'] = $val ['com_id'];
							$where2 ['ser_id'] = $ser_id;
							$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
							
							foreach ( $opcode as $m )
							{
								$val ['mem_id'] = $this->input->post ( 'ms_id' );
								$val ['sercode'] = $m->sercode;
								
								$where ['mem_id'] = $val ['mem_id'];
								$where ['sercode'] = $val ['sercode'];
								$where ['com_id'] = $val ['com_id'];
								$where ['mem_typ'] = $val ['mem_typ'];
								
								$a = $this->db->where ( $where )->get ( 'package_assigner' );
								if ($a->num_rows () > 0)
								{
									$this->db->where ( $where );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
							}
							echo $x;
						}
						elseif ($this->input->post ( 'mem_typ' ) == 'ds' && $this->input->post ( 'ds_id' ))
						{
							// getting the service id from post pac id
							$where1 ['com_id'] = $val ['com_id'];
							$where1 ['pac_id'] = $val ['pac_id'];
							$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
							$ser_id = $ser [0]->ser_id;
							
							// getting all operators of that service id
 							//$where2 ['com_id'] = $val ['com_id'];
							$where2 ['ser_id'] = $ser_id;
							$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
							
							foreach ( $opcode as $m )
							{
								$val ['mem_id'] = $this->input->post ( 'ds_id' );
								$val ['sercode'] = $m->sercode;
								
								$where ['mem_id'] = $val ['mem_id'];
								$where ['sercode'] = $val ['sercode'];
								$where ['com_id'] = $val ['com_id'];
								$where ['mem_typ'] = $val ['mem_typ'];
								
								$a = $this->db->where ( $where )->get ( 'package_assigner' );
								if ($a->num_rows () > 0)
								{
									$this->db->where ( $where );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
							}
							echo $x;
						}
						elseif ($this->input->post ( 'mem_typ' ) == 'rt' && $this->input->post ( 'rt_id' ))
						{
							// getting the service id from post pac id
							$where1 ['com_id'] = $val ['com_id'];
							$where1 ['pac_id'] = $val ['pac_id'];
							$ser = $this->db->select ( "ser_id" )->where ( $where1 )->get ( 'packages_details' )->result ();
							$ser_id = $ser [0]->ser_id;
							
							// getting all operators from service id($ser_id)
							//$where2 ['com_id'] = $val ['com_id'];
							$where2 ['ser_id'] = $ser_id;
							$opcode = $this->db->select ( "sercode" )->where ( $where2 )->get ( 'operator_details' )->result ();
							
							foreach ( $opcode as $m )
							{
								$val ['mem_id'] = $this->input->post ( 'rt_id' );
								$val ['sercode'] = $m->sercode;
								
								$where ['mem_id'] = $val ['mem_id'];
								$where ['sercode'] = $val ['sercode'];
								$where ['com_id'] = $val ['com_id'];
								$where ['mem_typ'] = $val ['mem_typ'];
								
								$a = $this->db->where ( $where )->get ( 'package_assigner' );
								if ($a->num_rows () > 0)
								{
									$this->db->where ( $where );
									$c = $this->db->update ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
								else
								{
									$c = $this->db->insert ( 'package_assigner', $val );
									$x=$this->db->affected_rows ( $c );
								}
							}
							echo $x;
						}
					}
			}// else add condition end here
		} 
		else 
		{
			echo validation_errors ();
		}
	}
	function view() 
	{
		$where ['com_id'] = $this->session->userdata ( 'com_id' ); // uncomment when testing with session
		$where ["status"] = 1;
		
		if (isset ( $_GET ['id'] ))
			$where ['ser_id'] = $_GET ['id'];
		
		if (isset ( $_GET ['data'] ))
			$select = $_GET ['data'];
		else
			$select = "*";
		
		$return = $this->Mdl_package_assigner_back->view_data ( $where, $select );
		$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $return->result_array () ) );
	}
	
}
?>
