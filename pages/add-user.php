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
          <form class="text-sm" action="./process.php?data=user&action=save" method="post">
            <div class="form-group">
              <label for="username">Fullname</label>
              <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Masukkan nama lengkap" autocomplete="off" autofocus required>
            </div>
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username untuk akses login" autocomplete="off" autofocus required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" id="secret" name="password" placeholder="Masukkan kata sandi untuk akses login" aria-label="Recipient's username" aria-describedby="button-addon2" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <input type="checkbox" name="check" id="check">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="group">Group</label>
              <select class="custom-select" id="group" name="group">
              <?php
              $sqlRole = "SELECT * FROM role_group";
              $resultRole = $conn->query($sqlRole);
              while($row = $resultRole->fetch_assoc()) : ?>
                <option value="<?=$row['role_group_id']?>"><?=ucfirst($row['role_name'])?></option>
              <?php endwhile ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-brand float-right pr-3 pl-3 pt-2 pb-2 mt-3">Buat User</button>
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
      } else{
        $("#group-description").hide();
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