<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT r.*, i.name as `incident` from `report_list` r inner join incident_list i on r.incident_id = i.id where r.id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
    if(isset($id) && $id > 0){
        $team_query = $conn->query("SELECT concat(rt.name,' ', t.code) as `team` from team_list t inner join respondent_type_list rt on t.respondent_type = rt.id where t.id in (SELECT team_id from `report_teams` where report_id = '{$id}') order by `team` asc");
        if($team_query->num_rows > 0){
            $teams = array_column($team_query->fetch_all(MYSQLI_ASSOC),'team');
            $teams = implode(", ", $teams);
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
    #cimg{
        width:100%;
        max-height:20vh;
        object-fit:scale-down;
        object-position:center center
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <dl>
                <dt class="text-muted">Report DateTime</dt>
                <dd class="pl-4"><?= isset($report_datetime) ? date("F d, Y h:i A", strtotime($report_datetime)) : '' ?></dd>
                <dt class="text-muted">Incident Type</dt>
                <dd class="pl-4"><h4><b><?= isset($incident) ? $incident : "N/A" ?></b></h4></dd>
                <dt class="text-muted">Location</dt>
                <dd class="pl-4"><?= isset($location) ? $location : "" ?></dd>
                <dt class="text-muted">Remarks</dt>
                <dd class="pl-4"><?= isset($remarks) ? $remarks : '' ?></dd>
            </dl>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <table class="table table-stripped table-bordered" id="team-tbl">
                <colgroup>
                    <col width="100%">
                </colgroup>
                <thead>
                    <tr class="bg-gradient-dark">
                        <th class="text-center py-1">Responders Team</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($id)): 
                    $qry = $conn->query("SELECT t.id, concat(rt.name, ' ', t.code) as `team` FROM `team_list` t inner join respondent_type_list rt on t.respondent_type = rt.id where t.id in (SELECT `team_id` FROM `report_teams` where report_id = '{$id}') ");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-2 py-1">
                            <span class="team_name"><?= $row['team'] ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <dl>
                <dt class="text-muted">Status</dt>
                <dd class="pl-4">
                    <?php if($status == 0): ?>
                        <span class="badge badge-secondary px-3 rounded-pill">Pending</span>
                    <?php else: ?>
                        <span class="badge badge-success px-3 rounded-pill">Done</span>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    </div>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>