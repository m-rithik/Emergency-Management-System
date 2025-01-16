<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
$date = isset($_GET['date']) ? $_GET['date'] :date('Y-m-d');    
$id = isset($_GET['id']) ? $_GET['id'] : 0;   
$incident = "";
 
?>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">Daily Incident Report</h3>
		<div class="card-tools">
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
            <fieldset class="border">
                <legend class="w-auto px-2">Filter</legend>
                <form action="" id="search-filter">
                    <div class="row align-items-end mx-0">
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="date" class="control-label">Choose Date</label>
                                <input type="date" name="date" class="form-control form-control-sm" value="<?= $date ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="id" class="control-label">Incident Type</label>
                                <select name="id" id="id" class="form-control form-control-sm rounded-0" required="required">
                                    <?php 
                                    $incident_qry = $conn->query("SELECT * FROM `incident_list` where delete_flag = 0 and `status` = 1 order by `name` asc");
                                    while($row = $incident_qry->fetch_assoc()):
                                        if($id == 0)
                                            $id = $row['id'];
                                        if($id == $row['id'])
                                            $incident = $row['name'];
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($id) && $id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
			                    <button class="btn btn-flat btn-primary btn-sm me-2"><span class="fas fa-filter"></span>  Filter</button>
			                    <a href="javascript:void(0)" id="print" class="btn btn-flat btn-light border btn-sm"><span class="fas fa-print"></span>  Print</a>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
        <div class="clear-fix mb-2"></div>
        <div class="container-fluid" id="printout">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="40%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Report DateTime</th>
						<th>Incident</th>
						<th>Location</th>
						<th>Dispatched Teams</th>
					</tr>
				</thead>
				<tbody>
					<?php 
                    $i=1;
					$qry = $conn->query("SELECT r.*, i.name as `incident` from `report_list` r inner join incident_list i on r.incident_id = i.id where date(report_datetime) = '{$date}' and incident_id = '{$id}' order by unix_timestamp(r.report_datetime) desc ");
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
							<td><p class="m-0"><?php echo !empty($teams) ? $teams : "N/A" ?></p></td>
						</tr>
					<?php endwhile; ?>
                    <?php if($qry->num_rows < 1): ?>
                        <tr>
                            <td class="px-2 py-1 text-center" colspan='5'>No data</td>
                        </tr>
                    <?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<noscript id="print-header">
<div class="d-flex w-100 align-items-center">
    <div class="col-2 text-center">
        <img src="<?= validate_image($_settings->info('logo')) ?>" class="img-thumbnail rounded-circle" style="width:4.5em;height:4.5em" alt="">
    </div>
    <div class="col-8">
        <div style="line-height:.5em">
            <h4 class="text-center m-0"><b><?= $_settings->info('name') ?></b></h4>
            <h3 class="text-center m-0"><b>Daily <?= $incident ?> Incident Report</b></h3>
            <div class="text-center">as of</div>
            <h5 class="text-center m-0"><b><?= date("F d, Y", strtotime($date)) ?></b></h5>
        </div>
    </div>
</div>
<hr>
</noscript>
<script>
	$(document).ready(function(){
		$('table td,table th').addClass('py-1 px-2 align-middle')
        $('#search-filter').submit(function(e){
            e.preventDefault()
            location.href = "./?page=reports/daily_type_report&"+$(this).serialize()
        })
        $('#print').click(function(){
            var h = $('head').clone()
            var ph = $($('noscript#print-header').html()).clone()
            var p = $('#printout').clone()
            h.find('title').text("Daily Incident Report - Print View")
            start_loader()
            var nw = window.open("", "_blank", "width=950, height=900")
                    $(nw.document).find('head').append(h.html())
                    $(nw.document).find('body').append(ph)
                    $(nw.document).find('body').append(p)
                     nw.document.close()
                setTimeout(()=>{
                    nw.print()
                    setTimeout(()=>{
                        nw.close()
                        end_loader()
                    },300)
                },300)

        })
	})
</script>