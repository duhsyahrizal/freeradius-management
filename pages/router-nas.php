<div class="container-fluid">
  <div class="row px-2">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <h1 class="m-0 text-dark">Router Nas</h1>
    </div>
  </div>
</div>
<!-- Main content -->
<section class="content px-2">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fa fa-hdd mr-1"></i> <?=$title?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <a href="admin.php?task=add-nas" class="btn btn-primary btn-brand btn-sm"><i class="fa fa-plus mr-1"></i> Buat Router</a>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered dt-responsive nowrap text-sm" id="alltable">
                <thead class="bg-brand">
                    <tr>
                    <th style="width: 5%;" scope="col">No</th>
                    <th scope="col">NAS IP Router</th>
                    <th scope="col">Nama Router</th>
                    <th scope="col">Secret</th>
                    <th style="width: 11%;" align="middle" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                    $num = 0;
                      while($row=$nas->fetch_assoc()) :
                        $num=$num+1
                  ?>
                    <tr>
                    <td class="text-center"><?= $num ?></td>
                    <td><?= $row['nasname'] ?></td>
                    <td><?= $row['shortname'] ?></td>
                    <td><?= $row['secret'] ?></td>
                    <td><a class="btn btn-info btn-brand btn-sm" href="./admin.php?task=edit-nas&id=<?= $row['id']?>"><i class="fas fa-pen"></i></a> <button class="btn btn-danger btn-sm" onclick="deleteConfirm('<?=$row['id']?>','<?=$row['shortname']?>')"><i class="px-1 far fa-trash-alt"></i></button></td>
                    </tr>
                    <?php 
                      endwhile;
                    ?>
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

  function disableUser(id){
    var username = $(".username").val();
    $.ajax({
      method: "GET",
      url: "./userman/process.php?data=user&action=get_status_user",
      data: {
        user_id: id,
      },
      success: function(res) {
        if(res == 'disable'){
          Swal.fire({
            title: 'Action Enable',
            text: "Are you sure to Enable this user?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Enable it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                method: "GET",
                url: "./userman/process.php?data=user&action=disable_user",
                data: {
                  user_id: id,
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
                      'Failed.',
                      'error'
                    )
                  }
                }
              })
            }
          })
        }
        else if(res == 'active'){
          Swal.fire({
            title: 'Action Disable',
            text: "Are you sure to Disable this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Disable it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                method: "GET",
                url: "./userman/process.php?data=user&action=disable_user",
                data: {
                  user_id: id,
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
                      'Failed.',
                      'error'
                    )
                  }
                }
              })
            }
          })
        }
      }
    })
  }

  function deleteConfirm(id, shortname){
    Swal.fire({
      title: 'Hapus Data',
      text: "Yakin ingin menghapus router nas ("+shortname+")?",
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
            url: "./process.php?data=nas&action=delete",
            data: {
              id: id,
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
    