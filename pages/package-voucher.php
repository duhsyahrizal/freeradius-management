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
            <h3 class="card-title"><i class="fa fa-user-plus mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="table-responsive-sm table-responsive-md">
            <table class="table table-bordered dt-responsive nowrap text-sm" style="width:100%" id="alltable">
              <thead>
                <tr class="bg-brand">
                  <th style="width: 5%;" scope="col">No</th>
                  <th scope="col">Username</th>
                  <th scope="col">Paket</th>
                  <th scope="col">Tipe Kuota</th>
                  <th style="width: 5%;" scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $num = 0;
                while($row = $resultUserGroups->fetch_assoc()) :
                  $group_bonus = ($row['priority'] == 0) ? $row['groupname'] : '';
               ?>
                <tr>
                  <td class="text-center"><?= $num=$num+1 ?></td>
                  <td><?= $row['username'] ?></td>
                  <td><span class="badge badge-primary bg-brand"><?= $row['groupname'] ?></span></td>
                  <td><span class="badge badge-<?= ($row['priority'] > 0) ? 'primary bg-brand' : 'warning text-white' ?>"><i class="fas fa-<?= ($row['priority'] > 0) ? 'check' : 'dollar-sign' ?> mr-1"></i><?= ($row['priority'] > 0) ? 'Utama' : 'Bonus' ?></span></td>
                  <td align="middle"><?= ($row['priority'] > 0) ? '<button type="button" class="btn btn-primary btn-sm" onclick="openModalVoucher(`'.$row["username"].'`)"><i class="fas fa-user-tag"> </i></button>' : '' ?> <?= ($row['priority'] < 1) ? '<button class="btn btn-danger btn-sm" onclick="deleteConfirm(`'.$row["username"].'`, `'.$group_bonus.'`)"><i class="px-1 far fa-trash-alt"></i></button>' : '' ?></td>
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
            <h5 class="modal-title" id="exampleModalLabel">Tambah Bonus Voucher (<span id="name"></span>)</h5>
          </div>
          <div class="modal-body px-4">
            <input type="hidden" id="username">
            <div class="form-group">
              <label for="billing">Pilih Paket Billing</label>
              <select class="custom-select" id="billing">
              <?php foreach($package as $row) : ?>
                <option value="<?=$row['name']?>"><?=$row['name']?></option>
              <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" onclick="assignProfile()" class="btn btn-primary btn-brand">Tambahkan</button>
          </div>
        </div>
      </div>
    </div>

<script>

  function getStatus(id){
    console.log(id);
  }

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

  function openModalVoucher(username){
    var username = username;
    $('#name').text(username);
    $('#username').val(username);
    $('#modal-voucher').modal();
  }

  function deleteConfirm(username, groupname){
    Swal.fire({
      title: 'Hapus Bonus Paket',
      text: "Yakin ingin menghapus bonus paket user ("+username+")?",
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
            url: "./process.php?data=voucher&action=delete-bonus",
            data: {
              username: username,
              groupname: groupname
            },
            success: function(res) {
              if (res == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
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
                  'Gagal Menghapus Bonus Paket.',
                  'error'
                )
              }
            }
          })
        }
    })
  }

  function assignProfile(){
    Swal.fire({
      title: 'Tambah Bonus Paket',
      text: "Yakin ingin menambah Bonus Paket?",
      icon: 'info',
      showCancelButton: true,
      cancelButtonText: 'Tidak jadi',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tambah Bonus'
    }).then((result) => {
        let username = $('#username').val(); 
        let billing = $('#billing').val(); 
        if (result.isConfirmed) {
          $.ajax({
            method: "POST",
            url: "./process.php?data=voucher&action=assign-profile",
            data: {
              username: username,
              billing: billing
            },
            success: function(res) {
              console.log(res);
              if (res == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
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
                  'Gagal Menambah Bonus Paket.',
                  'error'
                )
              }
            }
          })
        }
    })
  }

    
</script>