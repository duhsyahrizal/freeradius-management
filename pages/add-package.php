<!-- Main content -->
<section class="content pt-3 pb-1 px-2">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="far fa-address-card mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <button onclick="goBack()" class="btn btn-white btn-sm"><i class="fa fa-arrow-left px-1"></i> <strong>Kembali</strong></button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body text-sm">
          <form action="./process.php?data=package&action=save" method="post">
            <div class="form-row">
              <div class="form-group col-6">
                <label for="name">Nama Paket</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Paket" autocomplete="off" autofocus required>
              </div>
              <div class="form-group col-6">
                <label for="price">Harga Paket</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Masukkan Nominal Harga Paket" autocomplete="off" required>
              </div>
            </div>
            <div class="form-row">
              <div class="col-6">
                <label for="type">Tipe Paket</label>
                <div class="input-group">
                  <select class="custom-select" id="type" name="type" aria-describedby="typeHelp">
                    <option value="volume">Volume</option>
                    <option value="speed">Speed Limit</option>
                  </select>
                </div>
              </div>
              <div class="col" id="upload_div">
                <label for="limit">Limit Upload</label>
                <div class="input-group">
                  <select class="custom-select" name="upload" aria-describedby="typeHelp">
                    <?php foreach($rate_limits as $row) : ?>
                      <option value="<?=$row?>"><?=$row?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      KB
                    </div>
                  </div>
                </div>
              </div>
              <div class="col" id="download_div">
                <label for="limit">Limit Download</label>
                <div class="input-group">
                  <select class="custom-select" name="download" aria-describedby="typeHelp">
                    <?php foreach($rate_limits as $row) : ?>
                      <option value="<?=$row?>"><?=$row?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      KB
                    </div>
                  </div>
                </div>
              </div>
              <div class="col" id="volume">
                <label for="limit">Total Volume</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="volume" placeholder="Masukkan total Volume Kuota dalam format MB" autocomplete="off">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      MB
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-brand pr-3 pl-3 pt-2 pb-2 mt-3 float-right">Buat Paket</button>
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

<script>
  $('#upload_div').hide();
  $('#download_div').hide();
  $('#type').on('change', function() {
    if($(this).val() == 'volume') {
      $('#volume').show();
      $('#upload_div').hide();
      $('#download_div').hide();
    } else {
      $('#upload_div').show();
      $('#download_div').show();
      $('#volume').hide();
    }
  });
</script>