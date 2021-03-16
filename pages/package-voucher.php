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
          <div class="table-responsive-sm table-responsive-md">
            <table class="table table-bordered dt-responsive nowrap text-sm" style="width:100%" id="alltable">
              <thead>
                <tr class="bg-brand">
                  <th style="width: 5%;" scope="col">No</th>
                  <th scope="col">Username</th>
                  <th scope="col">Paket</th>
                  <th style="width: 10%;" scope="col">Berbagi Pengguna</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Status</th>
                  <th style="width: 5%;" scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $num = 0;
                while($row = $resultUsers->fetch_assoc()) :
               ?>
                <tr>
                  <td class="text-center"><?= $num=$num+1 ?></td>
                  <td><?= $row['username'] ?></td>
                  <td><span class="badge badge-primary bg-brand"><?= $row['package_name'] ?></span></td>
                  <td><span class="badge badge-primary bg-brand"><?= $row['shared_users'] ?></span></td>
                  <td>Rp. <?= number_format($row['price'], 2, ",", ".") ?></td>
                  <td><span class="badge <?= (strtotime($row['end_until']) < time()) ? 'badge-danger' : 'badge-success' ?>"><?= (strtotime($row['end_until']) < time()) ? 'Expired' : 'Active' ?></span></td>
                  <td class="py-2" align="middle"><button type="button" class="btn btn-primary btn-sm" onclick="openModalVoucher('<?=$row['username']?>')"><i class="fas fa-user-tag"> </i></button></td>
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
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Voucher</h5>
          </div>
          <div class="modal-body">
                
          </div>
          <div class="modal-footer">
            <button type="button" onclick="saveData()" class="btn btn-primary btn-brand">Save Data</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

  function openModalVoucher(voucher){
    $('#modal-voucher').modal();
  }
  function assignVoucher(){
    Swal.fire({
      title: 'Tambah Bonus Voucher',
      text: "Yakin ingin menambah Bonus Voucher untuk user ("+voucher+")?",
      icon: 'info',
      showCancelButton: true,
      cancelButtonText: 'Tidak jadi',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Tambah Bonus'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "POST",
            url: "./process.php?data=voucher&action=assign-voucher",
            data: {
              username: voucher,
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
                  'Gagal Menambah Bonus voucher.',
                  'error'
                )
              }
            }
          })
        }
    })
  }

    
</script>