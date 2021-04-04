<div class="container-fluid">
  <div class="row px-2">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <h1 class="m-0 text-dark">Dashboard</h1>
    </div>
  </div>
</div>
<!-- Main content -->
<section class="content px-2">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $total_router ?></h3>

                <p>Router NAS</p>
              </div>
              <div class="icon">
                <i class="ion ion-nuclear"></i>
              </div>
              <a href="admin.php?task=router-nas" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $total_paket ?></h3>

                <p>Total Paket</p>
              </div>
              <div class="icon">
                <i class="ion ion-speedometer"></i>
              </div>
              <a href="admin.php?task=package-list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner text-white">
                <h3><?= $total_active ?></h3>

                <p>Pengguna Aktif</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-people"></i>
              </div>
              <a href="admin.php?task=user-active" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $total_user ?></h3>

                <p>Total Voucher</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="admin.php?task=voucher-list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
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

  function deleteUser(id, shortname){
    Swal.fire({
      title: 'Action Delete',
      text: "Are you sure to Delete Nas ("+shortname+") ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "./userman/process.php?data=user&action=delete",
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

    
</script>
    