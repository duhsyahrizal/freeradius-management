<!-- Main content -->
<div class="container-fluid">
  <div class="row px-2">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <h1 class="m-0 text-dark"><?= $title ?></h1>
    </div>
  </div>
</div>
<section class="content pt-2 pb-1 px-2">
  <div class="container-fluid">
  <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fa fa-users mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <a href="admin.php?task=add-voucher" class="btn btn-primary btn-brand btn-sm"><i class="fa fa-user-plus mr-1"></i> Buat Voucher</a>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="">
            <table class="table table-responsive-sm table-responsive-md table-bordered dt-responsive nowrap text-sm" style="width:100%" id="alltable">
              <thead>
                <tr class="bg-brand">
                  <th style="width: 5%;" scope="col">No</th>
                  <th scope="col">Username</th>
                  <th scope="col">Password</th>
                  <th scope="col">Paket</th>
                  <th style="width: 15%;" scope="col">Berbagi Pengguna</th>
                  <th scope="col">Masa Aktif</th>
                  <th scope="col">Status</th>
                  <th style="width: 17%;" scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $num = 0;
                while($row = $resultUsers->fetch_assoc()) :
                  $quota = showQuota($row['username']);
               ?>
                <tr>
                  <td class="text-center"><?= $num=$num+1 ?></td>
                  <td><?= $row['username'] ?></td>
                  <td><?= $row['password'] ?></td>
                  <td><span class="badge badge-primary bg-brand px-1 py-1"><?= $row['name'] ?></span></td>
                  <td><span class="badge badge-primary bg-brand"><i class="fas fa-user mr-1"></i> <?= $row['shared_users'] ?></span></td>
                  <td><?= $row['end_until'] ?></td>
                  <td><span class="badge badge-<?= (strtotime($row['end_until']) < time()) ? 'danger' : 'success' ?> px-1 py-1"><i class="fas fa-user-<?= (strtotime($row['end_until']) < time()) ? 'times' : 'check' ?> mr-1"></i> <?= (strtotime($row['end_until']) < time()) ? 'Expired' : 'Tersedia' ?></span></td>
                  <td class="py-2"><button type="button" class="btn btn-primary btn-sm" onclick="openModalKuota('<?=$row['username']?>', '<?=$quota?>', '<?=$row['end_until']?>')"><i class="fas fa-info-circle"> </i></button> <button type="button" class="btn btn-info btn-sm" onclick="openModalVoucher('<?=$row['username']?>', <?=$row['billing_id']?>, '<?=$row['billing_type']?>', <?=$row['price']?>)"><i class="fas fa-heartbeat"> </i></button> <a class="btn btn-info btn-brand btn-sm" href="./admin.php?task=edit-voucher&username=<?= $row['username']?>"><i class="fas fa-pen"></i></a> <button class="btn btn-danger btn-sm" onclick="deleteConfirm('<?=$row['username']?>')"><i class="px-1 far fa-trash-alt"></i></button></td>
                </tr>
              <?php 
                endwhile; 
              ?>
              </tbody>
            </table>
          </div>
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

<!-- Modal -->
<div class="modal fade" id="modal-voucher">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-brand px-4">
        <h5 class="modal-title" id="exampleModalLabel">Refill Voucher (<span id="voucher"></span>)</h5>
      </div>
      <div class="modal-body px-4">
        <input type="hidden" id="username">
        <input type="hidden" id="billing_id">
        <input type="hidden" id="type">
        <input type="hidden" id="price">
        <div class="form-group">
          <label for="payment">Metode Pembayaran</label>
          <select class="custom-select" id="payment" name="payment">
            <option value="1">Cash</option>
            <option value="2">Gratis</option>
            <option value="3">Transfer</option>
            <option value="4">Hutang</option>
          </select>
        </div>
        <div id="group-description" class="form-group d-none">
          <label for="description">Keterangan</label>
          <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="refillVoucher()" class="btn btn-primary btn-brand">Refill</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
    <div class="modal-header bg-brand">
      <h5 class="modal-title ml-2" id="exampleModalLabel">Detail Pemakaian (<span id="name"></span>)</h5>
      </button>
      </div>
      <div class="modal-body px-4">
      <fieldset class="px-3" disabled>
        <div class="form-group">
          <label for="disabledTextInput">Username</label>
          <input type="text" id="detail_username" class="form-control">
        </div>
        <div class="form-group">
          <label for="disabledTextInput">Kuota Pemakaian</label>
          <input type="text" id="kuota" class="form-control">
        </div>
        <div class="form-group">
          <label for="disabledTextInput">Masa Aktif</label>
          <input type="text" id="expired" class="form-control">
        </div>
      </fieldset>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

  $("#payment").on("change", function(){
    $(this).find("option:selected").each(function(){
      var payment = $(this).attr("value");
      // alert(payment);
      if(payment != '1'){
        $("#group-description").removeClass('d-none');
      } else{
        $("#group-description").addClass('d-none');
      }
    });
  });

  $(document).ready( function () {
    $('.openModal').on('click', function(){
      var user_id = $(this).attr('data-id');
      console.log(user_id);
      $.ajax({
        method: "GET",
        url:"./modal/modal_user.php?id="+user_id,
        cache:false,
        success:function(result){
          $(".modal-content").html(result);
      }});
    });
  });

  function openModalVoucher(username, billing_id, type, price){
    $('#voucher').text(username);
    $('#username').val(username);
    $('#billing_id').val(billing_id);
    $('#type').val(type);
    $('#price').val(price);
    $('#modal-voucher').modal();
  }

  function openModalKuota(username, kuota, expired){
    $('#name').text(username);
    $('#detail_username').val(username);
    $('#kuota').val(kuota);
    $('#expired').val(expired);
    $('#modal-detail').modal();
  }

  function refillVoucher(){
    let username = $('#username').val();
    let billing_id = $('#billing_id').val();
    let type = $('#type').val();
    let price = $('#price').val();
    let payment = $('#payment').val();
    let description = $('#description').val();
    Swal.fire({
      title: 'Refill Voucher',
      text: "Yakin ingin me-refill voucher ("+username+")?",
      icon: 'info',
      showCancelButton: true,
      cancelButtonText: 'Tidak jadi',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Refill'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "POST",
            url: "./process.php?data=voucher&action=refill",
            data: {
              username: username,
              billing_id: billing_id,
              type: type,
              price: price,
              payment: payment,
              description: description
            },
            success: function(res) {
              let data = JSON.parse(res);
              if (data.status == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  text: ''+data.message+'',
                  title: 'Success.',
                  showConfirmButton: false,
                  timer: 1000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                      //console.log('I was closed by the timer')
                      location.reload();
                    }
                })
              }else{
                console.log(res)
                Swal.fire(
                  'Error!',
                  ''+data.message+'',
                  'error'
                )
              }
            }
          })
        }
    })
  }

  function deleteConfirm(voucher){
    Swal.fire({
      title: 'Hapus Voucher',
      text: "Yakin ingin menghapus voucher ("+voucher+")?",
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'Tidak jadi',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus saja!'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "./process.php?data=voucher&action=delete",
            data: {
              username: voucher,
            },
            success: function(res) {
              let data = JSON.parse(res);
              if (data.status == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  text: ''+data.message+'',
                  title: 'Success.',
                  showConfirmButton: false,
                  timer: 1000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                      //console.log('I was closed by the timer')
                      location.reload();
                    }
                })
              }else{
                Swal.fire(
                  'Error!',
                  ''+data.message+'',
                  'error'
                )
              }
            }
          })
        }
    })
  }

    
</script>