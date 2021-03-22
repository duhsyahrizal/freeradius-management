<div class="container-fluid">
  <div class="row px-2">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <h1 class="m-0 text-dark"><?= $title ?></h1>
    </div>
  </div>
</div>
<!-- Main content -->
<section class="content px-2">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
      <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="far fa-file-alt mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            </div>
            <!-- /.card-tools -->
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form action="admin.php?task=report-data" method="get">
            <!-- Date and time range -->
            <input type="hidden" name="task" value="report-data">
            <div class="form-group">
              <label>Start Date</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></i></span>
                </div>
                <input type="text" class="form-control float-right reservationtime" name="start_date" value="<?=$monthAgo?>" placeholder="Please set start date..">
              </div>
            </div>
            <div class="form-group">
              <label>End Date</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></i></span>
                </div>
                <input type="text" class="form-control float-right reservationtime" name="end_date" value="<?=$month?>" placeholder="Please set end date..">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm btn-brand float-right pr-3 pl-3 pt-2 pb-2  mt-3">Lihat Laporan</button>
          </form>
        </div>
        <!-- /.card-body -->
        <!-- <div class="card-footer">
            The footer of the card
        </div> -->
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
  </div>  
</section>
<!-- /.Main content -->