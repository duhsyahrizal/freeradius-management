<!-- Main content -->
<section class="content pt-3 pb-1 px-2">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fas fa-user mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <button onclick="goBack()" class="btn btn-white btn-sm"><i class="fa fa-arrow-left px-1"></i> <strong>Kembali</strong></button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <?php while($row = $resultQuery->fetch_all()) : ?>
          <form class="text-sm" action="./process.php?data=voucher&action=update" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="hidden" id="package_type" value="<?=$userbilling['package_type']?>" name="package_type">
              <input type="hidden" id="before_shared_users" value="<?=$userbilling['shared_users']?>" name="before_shared_users">
              <input type="text" class="form-control" id="username" name="username" value="<?=$username?>" placeholder="Masukkan username untuk voucher billing" autocomplete="off" autofocus required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="secret" name="password" value="<?=$userbilling['password']?>" placeholder="Masukkan kata sandi untuk voucher billing" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <input type="checkbox" name="check" id="check">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row mb-3">
              <div class="col">
                <label for="profile">Paket Billing</label>
                <input type="text" class="form-control" id="profile" name="profile" value="<?=$userbilling['name']?>" disabled>
              </div>
              <div class="col" id="simultan">
                <label for="shared_users">Pilih Berbagi Pengguna</label>
                <select class="custom-select" id="shared_users" name="shared_users">
                <?php foreach($option_shared_user as $row) : ?>
                  <option <?= ($userbilling['shared_users'] == $row) ? 'selected' : '' ?>><?=$row?></option>
                <?php endforeach ?>
                </select>
              </div>
              <div class="col" id="group-upload">
                <label for="upload">Upload</label>
                <div class="input-group">
                  <select class="custom-select" id="upload" name="upload">
                  <?php foreach($rate_limits as $row) : ?>
                    <option value="<?=$row?>"><?=$row?></option>
                  <?php endforeach; ?>
                  </select>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      Kbps
                    </div>
                  </div>
                </div>
              </div>
              <div class="col" id="group-download">
                <label for="download">Download</label>
                <div class="input-group">
                  <select class="custom-select" id="download" name="download">
                  <?php foreach($rate_limits as $row) : ?>
                    <option value="<?=$row?>"><?=$row?></option>
                  <?php endforeach; ?>
                  </select>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      Kbps
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <h6 class="mt-4 mb-2 text-secondary">Informasi Tambahan (Optional)</h6>
            <div class="form-row mb-3">
              <div class="col-6">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" class="form-control" name="fullname" placeholder="Ketikkan Nama Lengkap">
              </div>
              <div class="col-6">
                <label>Tanggal Lahir</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input placeholder="Pilih tanggal lahir pengguna" type="text" class="form-control datepicker" name="date_of_birth" autocomplete="off" aria-describedby="birthdayHelp">
                </div>
              </div>
            </div>
            <div class="form-row mb-3">
              <div class="col-6">
                <label for="boarding_name">Nama Kost'an</label>
                <input type="text" class="form-control" name="boarding_name" placeholder="Nama kost'an pengguna">
              </div>
              <div class="col-6">
                <label for="telephone">Telp/Whatsapp</label>
                <input type="text" class="form-control" name="telephone" placeholder="Nomor telp/whatsapp pengguna">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-brand float-right pr-3 pl-3 pt-2 pb-2 mt-3">Perbarui</button>
          </form>
        </div>
        <?php endwhile; ?>
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
    
    // initial billing type
    let username = $('#username').val();
    let type = $('#package_type').val();
    let limit = $('#package_limit').val();
    speed = limit/1024;
    if(type == 'volume') {
      $('#upload').val(speed);
      $('#download').val(speed);
      $('#group-upload').show();
      $('#group-download').show();
    } else {
      $('#group-upload').hide();
      $('#group-download').hide();
      $('#simultan').removeClass('col-4');
      $('#simultan').addClass('col');
    }
  // $('#updown').removeAttr('disabled');
</script>