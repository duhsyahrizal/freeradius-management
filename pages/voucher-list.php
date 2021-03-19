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
                  <th style="width: 14%;" scope="col">Aksi</th>
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
                  <td><?= $row['password'] ?></td>
                  <td><span class="badge badge-primary bg-brand px-1 py-1"><?= $row['name'] ?></span></td>
                  <td><span class="badge badge-primary bg-brand"><i class="fas fa-user mr-1"></i> <?= $row['shared_users'] ?></span></td>
                  <td><?= $row['end_until'] ?></td>
                  <td><span class="badge badge-<?= (strtotime($row['end_until']) < time()) ? 'danger' : 'success' ?> px-1 py-1"><i class="fas fa-user-<?= (strtotime($row['end_until']) < time()) ? 'times' : 'check' ?> mr-1"></i> <?= (strtotime($row['end_until']) < time()) ? 'Expired' : 'Tersedia' ?></span></td>
                  <td class="py-2"><button type="button" class="btn btn-success btn-sm" onclick="refillVoucher('<?=$row['username']?>', '<?=$row['billing_id']?>', '<?=$row['billing_type']?>', '<?=$row['price']?>')"><i class="fas fa-heartbeat"> </i></button> <a class="btn btn-info btn-brand btn-sm" href="./admin.php?task=edit-voucher&username=<?= $row['username']?>"><i class="far fa-edit"></i></a> <button class="btn btn-danger btn-sm" onclick="deleteConfirm('<?=$row['username']?>')"><i class="px-1 far fa-trash-alt"></i></button></td>
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
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <!-- Modal is redirected to external file (modal_{name}.php) -->
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
                  'Gagal Menghapus voucher.',
                  'error'
                )
              }
            }
          })
        }
    })
  }

  function refillVoucher(voucher, billing_id, type, price){
    Swal.fire({
      title: 'Refill Voucher',
      text: "Yakin ingin me-refill voucher ("+voucher+")?",
      icon: 'info',
      showCancelButton: true,
      cancelButtonText: 'Tidak jadi',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Refill'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "./process.php?data=voucher&action=refill",
            data: {
              username: voucher,
              billing_id: billing_id,
              type: type,
              price: price
            },
            success: function(res) {
              console.log(res)
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
                console.log(res)
                Swal.fire(
                  'Error!',
                  'Gagal Me-refill voucher.',
                  'error'
                )
              }
            }
          })
        }
    })
  }

    
</script>