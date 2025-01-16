<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `team_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="team-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="respondent_type" class="control-label">Responder Type</label>
			<select name="respondent_type" id="respondent_type" class="form-control form-control-sm rounded-0" required>
			<option value="" <?php echo !isset($respondent_type)? 'selected' : '' ?>></option>
			<?php 
			$respondent_types = $conn->query("SELECT * FROM `respondent_type_list` where delete_flag = 0 and `status` = 1 ".(isset($respondent_type) ? " or id = '{$respondent_type}' " : "")." order by `name` asc ");
			while($row = $respondent_types->fetch_assoc()):
			?>
			<option value="<?= $row['id'] ?>" <?php echo isset($respondent_type) && $respondent_type == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
			<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="code" class="control-label">Code</label>
			<input type="text" name="code" id="code" class="form-control form-control-sm rounded-0" value="<?php echo isset($code) ? $code : ''; ?>"  required/>
		</div>
		<div class="form-group">
			<label for="team_leader" class="control-label">Team Leader</label>
			<input type="text" name="team_leader" id="team_leader" class="form-control form-control-sm rounded-0" value="<?php echo isset($team_leader) ? $team_leader : ''; ?>"  required/>
		</div>
		<div class="form-group">
			<label for="status" class="control-label">Status</label>
			<select name="status" id="status" class="form-control form-control-sm rounded-0" required>
			<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
			<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#uni_modal').on('shown.bs.modal', function(){
			$('#respondent_type').select2({
				placeholder:'Please select here',
				width:'100%',
				dropdownParent:$('#uni_modal'),
				containerCssClass:'form-control form-control-sm rounded-0'
			})
		})
		$('#team-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_team",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload()
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0)
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>