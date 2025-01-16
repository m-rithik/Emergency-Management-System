<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_incident(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `incident_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "incident Name already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `incident_list` set {$data} ";
		}else{
			$sql = "UPDATE `incident_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$bid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New incident successfully saved.";
			else
				$resp['msg'] = " incident successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_incident(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `incident_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," incident successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_respondent_type(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `respondent_type_list` where `name` = '{$name}' and delete_flag = 0 ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Respondent Type already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `respondent_type_list` set {$data} ";
		}else{
			$sql = "UPDATE `respondent_type_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$bid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Respondent Type successfully saved.";
			else
				$resp['msg'] = " Respondent Type successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_respondent_type(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `respondent_type_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Respondent Type successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_team(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `team_list` where `code` = '{$code}' and respondent_type = '{$respondent_type}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Team Name already exists on the selected incident.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `team_list` set {$data} ";
		}else{
			$sql = "UPDATE `team_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$bid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New team successfully saved.";
			else
				$resp['msg'] = " team successfully updated.";
			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_team(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `team_list` set `delete_flag` = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," team successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function load_team(){
		extract($_POST);
		$data = [];
		$qry = $this->conn->query("SELECT t.id, concat(rt.name, ' ', t.code) as `team` FROM `team_list` t inner join `respondent_type_list` rt on t.respondent_type = rt.id where t.delete_flag = 0 and t.status = 1 and t.id not in (SELECT `team_id` FROM `report_teams` where report_id in (SELECT id FROM `report_list` where status = 0 ".(!empty($id) ? " and id != '{$id}' " : "")." )) order by `team` asc");
		while($row = $qry->fetch_assoc()){
			$data[] = $row;
		}
		return json_encode($data);
	}
	function save_report(){
		if(empty($_POST['id']))
		$_POST['user_id'] = $this->settings->userdata('id');
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `report_list` set {$data} ";
		}else{
			$sql = "UPDATE `report_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Report successfully saved.";
			else
				$resp['msg'] = " Report successfully updated.";
			$this->conn->query("DELETE FROM `report_teams` where report_id = '{$rid}'");
			if(isset($team_id)){
				$data = "";
				foreach($team_id as $k => $v){
					if(!empty($data)) $data .= ", ";
					$data .= "('{$rid}', '{$v}')";
				}
				if(!empty($data)){
					$sql2 = "INSERT INTO `report_teams` (`report_id`, `team_id`) VALUES {$data}";
					$save2 = $this->conn->query($sql2);
					if(!$save2){
						$resp['status'] = 'failed';
						if(empty($id)){
							$resp['msg'] = "Report has failed to save.";
							$this->conn->query("DELETE FROM `report_list` where id = '{$rid}'");
						}else{
							$resp['msg'] = " Report has failed to update.";
						}
						$resp['error'] = $this->conn->error;
						$resp['sql'] = $sql2;
					}
				}
			}			
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
			$resp['sql'] = $sql;

		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_report(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `report_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Report successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `transaction_list` set `status` = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction's status has failed to update.";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success', 'Transaction\'s Status has been updated successfully.');
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_incident':
		echo $Master->save_incident();
	break;
	case 'delete_incident':
		echo $Master->delete_incident();
	break;
	case 'save_team':
		echo $Master->save_team();
	break;
	case 'delete_team':
		echo $Master->delete_team();
	break;
	case 'load_team':
		echo $Master->load_team();
	break;
	case 'save_respondent_type':
		echo $Master->save_respondent_type();
	break;
	case 'delete_respondent_type':
		echo $Master->delete_respondent_type();
	break;
	case 'save_report':
		echo $Master->save_report();
	break;
	case 'delete_report':
		echo $Master->delete_report();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	default:
		// echo $sysset->index();
		break;
}