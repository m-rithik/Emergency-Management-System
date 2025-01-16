<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php $stat_arr = ["Pending","Done"]; ?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Incident Report <?= isset($_GET['status']) && isset($stat_arr[$_GET['status']]) ? "({$stat_arr[$_GET['status']]})" : "" ?></h3>
		<div class="card-tools">
			<?php if(!isset($_GET['status'])): ?>
			<?php 
			$count_pending = $conn->query("SELECT id FROM `report_list` where `status` = 0")->num_rows;
			?>
			<a href="./?page=incident_reports&status=0" class="btn btn-flat btn-gradient-light border"><span class="badge badge-danger rounded"><?= format_num($count_pending > 0 ? $count_pending : 0) ?></span>  Filter Pending</a>
			<?php else: ?>
			<a href="./?page=incident_reports" class="btn btn-flat btn-gradient-light border"><i class="fa fa-list"></i>  List All</a>
			<?php endif; ?>
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Report DateTime</th>
						<th>Incident</th>
						<th>Location</th>
						<th>Dispatched Teams</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$where = "";
					if(isset($_GET['status']))
						$where = " where r.`status` = '{$_GET['status']}' ";
					$qry = $conn->query("SELECT r.*, i.name as `incident` from `report_list` r inner join incident_list i on r.incident_id = i.id {$where} order by unix_timestamp(r.report_datetime) desc ");
					while($row = $qry->fetch_assoc()):
						$teams = "";
						$team_query = $conn->query("SELECT concat(rt.name,' ', t.code) as `team` from team_list t inner join respondent_type_list rt on t.respondent_type = rt.id where t.id in (SELECT team_id from `report_teams` where report_id = '{$row['id']}') order by `team` asc");
						if($team_query->num_rows > 0){
							$teams = array_column($team_query->fetch_all(MYSQLI_ASSOC),'team');
							$teams = implode(", ", $teams);
						}
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['report_datetime'])) ?></td>
							<td><?php echo $row['incident'] ?></td>
							<td><?php echo $row['location'] ?></td>
							<td><p class="m-0 truncate-1"><?php echo !empty($teams) ? $teams : "N/A" ?></p></td>
							<td class="text-center">
                                <?php if($row['status'] == 0): ?>
                                    <span class="badge badge-secondary px-3 rounded-pill">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-success px-3 rounded-pill">Done</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Incident Report permanently?","delete_report",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Incident Report","incident_reports/manage_report.php", "modal-lg")
		})
		$('.view_data').click(function(){
			uni_modal("<i class='fa fa-bars'></i> Incident Report Details","incident_reports/view_report.php?id="+$(this).attr('data-id'), "modal-lg")
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Update Incident Report Details","incident_reports/manage_report.php?id="+$(this).attr('data-id'), "modal-lg")
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [6] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_report($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_report",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>