<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
          <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-th-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Incident Types</span>
                <span class="info-box-number">
                  <?php 
                    $incident_type = $conn->query("SELECT * FROM incident_list where delete_flag = 0 and `status` = 1")->num_rows;
                    echo format_num($incident_type);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Respondent Types</span>
                <span class="info-box-number">
                  <?php 
                    $respondent_type = $conn->query("SELECT * FROM respondent_type_list where delete_flag = 0 and `status` = 1")->num_rows;
                    echo format_num($respondent_type);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Responders Team</span>
                <span class="info-box-number">
                  <?php 
                    $team = $conn->query("SELECT * FROM team_list where delete_flag = 0 and `status` = 1")->num_rows;
                    echo format_num($team);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Available Teams</span>
                <span class="info-box-number">
                  <?php 
                    $team = $conn->query("SELECT * FROM team_list where delete_flag = 0 and `status` = 1 and id not in (SELECT team_id from report_teams where report_id in (SELECT id FROM report_list where `status` = 0) ) ")->num_rows;
                    echo format_num($team);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
              <span class="info-box-icon bg-gradient-danger elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Unavailable Teams</span>
                <span class="info-box-number">
                  <?php 
                    $team = $conn->query("SELECT * FROM team_list where delete_flag = 0 and `status` = 1 and id in (SELECT team_id from report_teams where report_id in (SELECT id FROM report_list where `status` = 0) ) ")->num_rows;
                    echo format_num($team);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
<div class="container">
  <?php 
    $files = array();
      $fopen = scandir(base_app.'uploads/banner');
      foreach($fopen as $fname){
        if(in_array($fname,array('.','..')))
          continue;
        $files[]= validate_image('uploads/banner/'.$fname);
      }
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
      </a>
  </div>
</div>
