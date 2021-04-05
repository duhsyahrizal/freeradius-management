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
          <form class="text-sm" action="./process.php?data=voucher&action=save" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="hidden" id="package_type" value="" name="package_type">
              <input type="hidden" id="package_name" value="" name="package_name">
              <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username untuk voucher billing" autocomplete="off" autofocus required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="secret" name="password" placeholder="Masukkan kata sandi untuk voucher billing" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <input type="checkbox" name="check" id="check">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="profile">Pilih Paket Billing</label>
              <select class="custom-select" id="profile" name="profile">
              <?php foreach($package as $row) : ?>
                <option value="<?=$row['id']?>"><?=$row['name']?></option>
              <?php endforeach ?>
              </select>
            </div>
            <div class="form-row mb-3">
              <div class="col-4">
                <label for="idletimeout">Idle Timeout</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="idletimeout" name="idletimeout" autocomplete="off">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      Seconds
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-8">
                <label for="shared_users">Pilih Berbagi Pengguna</label>
                <select class="custom-select" id="shared_users" name="shared_users">
                <?php foreach($option_shared_user as $row) : ?>
                  <option value="<?=$row?>"><?=$row?></option>
                <?php endforeach ?>
                </select>
              </div>
            </div>
              <div class="form-row mb-3">
                <div class="col">
                  <div class="form-group">
                    <label for="start_hour">Jam Mulai</label>
                    <input type="text" class="form-control" id="start_hour" name="start_hour">
                  </div>
                </div>
                <div class="col">
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
                <div class="col">
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
            <div class="form-row mb-3">
              <div class="col-6">
                <label>Tanggal Mulai</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker px-2" id="start_date" name="start_date" autocomplete="off">
                </div>
              </div>
              <div class="col-6">
                <label>Tanggal Expired</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker px-2" id="end_date" name="end_date" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="payment">Metode Pembayaran</label>
              <select class="custom-select" id="payment" name="payment">
                <option value="1">Cash</option>
                <option value="2">Gratis</option>
                <option value="3">Transfer</option>
                <option value="4">Hutang</option>
              </select>
            </div>
            <div id="group-description" class="form-group">
              <label for="description">Keterangan</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
                  <input placeholder="Pilih tanggal lahir pengguna" type="text" class="form-control datepicker px-2" name="date_of_birth" autocomplete="off" aria-describedby="birthdayHelp">
                </div>
              </div>
            </div>
            <div class="form-row mb-3">
              <div class="col-6">
                <label for="boarding_house_name">Nama Kost'an</label>
                <input type="text" class="form-control" name="boarding_house_name" placeholder="Nama kost'an pengguna">
              </div>
              <div class="col-6">
                <label for="telephone">Telp/Whatsapp</label>
                <input type="text" class="form-control" name="telephone" placeholder="Nomor telp/whatsapp pengguna">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-brand float-right pr-3 pl-3 pt-2 pb-2 mt-3">Buat Voucher</button>
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
  $("#start_date").val("<?=$date?>");
  $("#end_date").val("<?=$endDate?>");
  $("#start_hour").val("<?=$created_at?>");
  $("#idletimeout").val("600");
  
  $("#group-description").hide();
  $("#payment").on("change", function(){
    $(this).find("option:selected").each(function(){
      var payment = $(this).attr("value");
      // alert(payment);
      if(payment != '1'){
        $("#group-description").show();
        $("#group-description").prop('required',true);
      } else{
        $("#group-description").hide();
        $("#group-description").prop('required',false);
      }
    });
  });

    // initial billing type
    let id = $('#profile').val();
    $.ajax({
      method: "GET",
      url: "./process.php?data=package&action=get-billing",
      data: {
        id: id,
      },
      success: function(res) {
        let data = JSON.parse(res);
        let type = data.billing_type;
        let name = data.name;
        let limit_upload = data.limit_upload;
        let limit_download = data.limit_download;

        $('#package_type').attr('value', type);
        $('#package_name').attr('value', name);

        if(type == 'volume') {
          $('select#upload option[value="2048"]').attr("selected",true);
          $('select#download option[value="2048"]').attr("selected",true);
          $('#shared_users').attr("disabled",false);
          $('#upload').removeAttr('disabled');
          $('#download').removeAttr('disabled');
        } else {
          $('select#shared_users option[value="2"]').attr("selected",true);
          $('#shared_users').attr("disabled",true);
          $('#upload').attr('disabled', 'disabled');
          $('#download').attr('disabled', 'disabled');
        }
      }
    })
  $('#profile').on('change', function() {
    let id = $(this).val();
    $.ajax({
      method: "GET",
      url: "./process.php?data=package&action=get-billing",
      data: {
        id: id,
      },
      success: function(res) {
        let data = JSON.parse(res);
        let type = data.billing_type;
        let name = data.name;
        let limit_upload = data.limit_upload/1024;
        let limit_download = data.limit_download/1024;

        $('#package_type').attr('value', type);
        $('#package_name').attr('value', name);

        if(type == 'volume') {;
          $('select#upload option[value="2048"]').attr("selected",true);
          $('select#download option[value="2048"]').attr("selected",true);
          $('select#shared_users option[value="2"]').removeAttr("selected");
          $('#shared_users').removeAttr("disabled");
          $('#upload').removeAttr('disabled');
          $('#download').removeAttr('disabled');
        } else {
          $('select#shared_users option[value="2"]').attr("selected",true);
          $('#shared_users').attr("disabled",true);
          $('#upload').attr('disabled', 'disabled');
          $('#download').attr('disabled', 'disabled');
        }
      }
    })
  })
  // $('#updown').removeAttr('disabled');
</script>