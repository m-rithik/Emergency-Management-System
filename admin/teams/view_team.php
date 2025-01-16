<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT t.*, rt.name as `type` from `team_list` t inner join respondent_type_list rt on t.respondent_type = rt.id  where t.id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
	<dl>
        <dt class="text-muted">Responder Type</dt>
        <dd class="pl-4"><?= isset($type) ? $type : "" ?></dd>
        <dt class="text-muted">Team Code</dt>
        <dd class="pl-4"><?= isset($code) ? $code : "" ?></dd>
        <dt class="text-muted">Team Leader</dt>
        <dd class="pl-4"><?= isset($team_leader) ? $team_leader : '' ?></dd>
        <dt class="text-muted">Status</dt>
        <dd class="pl-4">
            <?php if($status == 1): ?>
                <span class="badge badge-success px-3 rounded-pill">Active</span>
            <?php else: ?>
                <span class="badge badge-danger px-3 rounded-pill">Inactive</span>
            <?php endif; ?>
        </dd>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>