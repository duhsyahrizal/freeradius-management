<!-- Main content -->
<section class="content pt-3 pb-1 px-2">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fa fa-radiation-alt"></i> New Nas</h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <button onclick="goBack()" class="btn btn-white btn-sm"><i class="fa fa-arrow-left px-1"></i> <strong>Kembali</strong></button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body text-sm">
          <form action="./process.php?data=nas&action=save" method="post">
            <div class="form-group">
              <label for="nasname">IP Router</label>
              <input type="text" class="form-control" id="nasname" name="nasname" placeholder="Masukkan IP Router baru" required autofocus autocomplete="off">
            </div>
            <div class="form-group">
              <label for="secret">Secret</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="secret" name="secret" placeholder="Masukkan Secret untuk Router Baru" aria-describedby="button-addon2" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <input type="checkbox" name="check" id="check">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="shortname">Nama Router</label>
              <input type="text" class="form-control" id="shortname" name="shortname" placeholder="Masukkan Nama untuk Router baru " required="true" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-primary btn-brand pr-3 pl-3 pt-2 pb-2 float-right mt-3 text-sm">Apply</button>
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
  <!-- /.container -->
</section>
<!-- /.Main content -->
<script type="text/javascript">
 $(function(){
  $(".datepicker").datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true,
      todayHighlight: true,
  });
 });
</script>