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
            <h3 class="card-title"><i class="fa fa-user-tag mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <?= ($_SESSION['user'] != 'admin') ? '' : '<a href="admin.php?task=add-package" class="btn btn-primary btn-brand btn-sm"><i class="fa fa-plus mr-1"></i> Buat Paket</a>' ?>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered dt-responsive nowrap text-sm" id="alltable">
              <thead>
                <tr class="bg-brand">
                  <th style="width: 5%;" class="text-center" scope="col">No</th>
                  <th scope="col">Nama Paket</th>
                  <th scope="col">Volume/Speed</th>
                  <th scope="col">Tipe Kuota</th>
                  <th scope="col">Harga</th>
                  <?= ($_SESSION['user'] != 'admin') ? '' : '<th scope="col">Aksi</th>' ?>
                </tr>
              </thead>
              <tbody>
                <?php
                  $num = 0;
                  while($row=$package->fetch_assoc()) :
                    $num=$num+1;
                    switch($row['billing_type']) {
                      case 'speed' :
                        $updown = convert_bytes($row['limit_upload'],'K',0).' / '.convert_bytes($row['limit_download'],'K',0).' KB';
                      case 'volume' : 
                        $volume = number_format($row['volume']/1048576,0).' MB';
                    }
                ?>
                <tr>
                  <td><?=$num?></td>
                  <td><?=$row['name']?></td>
                  <td><?= ($row['billing_type'] == 'speed') ? $updown : $volume ?></td>
                  <td><?= ucfirst($row['billing_type']) ?></td>
                  <td>Rp. <?=number_format($row['price'], 2, ",", ".")?></td>
                  <?= ($_SESSION['user'] != 'admin') ? '' : '<td style="width: 11%;" align="middle"><a class="btn btn-info btn-brand btn-sm" href="./admin.php?task=edit-package&id='.$row["id"].'"><i class="fas fa-pen"></i></a> <button class="btn btn-danger btn-sm" onclick="deleteConfirm('.$row['id'].',`'.$row['name'].'`,`'.$row['billing_type'].'`)"><i class="px-1 far fa-trash-alt"></i></button></td>' ?>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
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
  $(document).ready( function () {
    $('.openModal').on('click', function(){
      var mikrotik = $(this).attr('data-mikrotik');
      console.log(mikrotik);
      $.ajax({url:"./modal/modal_profile.php?id="+mikrotik,cache:false,success:function(result){
          $(".modal-content").html(result);
      }});
    })
  });

  function deleteConfirm(id, name, type){
    Swal.fire({
      title: 'Action Delete',
      text: "Are you sure to delete package ("+name+") ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "./process.php?data=package&action=delete",
            data: {
              id: id,
              name: name,
              type: type
            },
            success: function(res) {
              let data = JSON.parse(res);
              if (data.status == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Success.',
                  text: ""+data.message+"",
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