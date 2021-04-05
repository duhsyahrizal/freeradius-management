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
            <h3 class="card-title"><?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <a href="admin.php?task=add-user" class="btn btn-primary btn-brand btn-sm"><i class="fa fa-user-plus mr-1"></i> Create User</a>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="table-responsive-sm table-responsive-md">
            <table class="table table-bordered dt-responsive nowrap text-sm" style="width:100%" id="user-table">
              <thead>
                <tr class="bg-brand">
                  <th style="width: 5%;" scope="col">No</th>
                  <th scope="col">Username</th>
                  <th scope="col">Fullname</th>
                  <th scope="col">Grup</th>
                  <th style="width: 12%;" scope="col">Manage Voucher</th>
                  <th style="width: 14%;" scope="col">Manage Paket</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sql = "SELECT * FROM bayhost_users
                INNER JOIN role_group ON role_group.role_group_id = bayhost_users.role";
                $result = $conn->query($sql);
                $num = 0;
                while($row = $result->fetch_assoc()) :
               ?>
                <tr>
                  <td><?= $num=$num+1 ?></td>
                  <td><?= $row['username'] ?></td>
                  <td><?= $row['fullname'] ?></td>
                  <td><?= $row['role_name'] ?></td>
                  <td align="middle"><?= ($row['manage_user'] == 1) ? '<span class="badge badge-success"><i class="px-1 py-1 fas fa-check"></i></span>' : '<span class="badge badge-danger"><i class="px-1 py-1 fas fa-times"></i></span>' ?></td>
                  <td align="middle"><?= ($row['manage_package'] == 1) ? '<span class="badge badge-success"><i class="px-1 py-1 fas fa-check"></i></span>' : '<span class="badge badge-danger"><i class="px-1 py-1 fas fa-times"></i></span>' ?></td>
                  <td style="width: 11%;" align="middle" class="py-2"><button type="button" class="btn btn-info btn-brand btn-sm" onclick="editUser(<?= $row['bayhost_user_id']?>)"><i class="fas fa-pen"></i></button> <button class="btn btn-danger btn-sm" onclick="deleteUser(<?=$row['bayhost_user_id']?>,'<?=$row['username']?>')"><i class="px-1 far fa-trash-alt"></i></button></td>
               </tr>
              <?php 
                 endwhile
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
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
    <div class="modal-header bg-brand">
      <h5 class="modal-title ml-2" id="exampleModalLabel">Edit User (<span id="name"></span>)</h5>
      </button>
      </div>
      <div class="modal-body px-4">
        <div>
          <div class="form-group">
            <label for="fullname">Fullname</label>
            <input type="hidden" id="user_id">
            <input type="text" id="fullname" class="form-control">
          </div>
          <div class="form-group">
            <label for="user">Username</label>
            <input type="text" id="username" class="form-control">
          </div>
          <div class="form-group">
            <label for="pass">Password</label>
            <input type="text" id="password" class="form-control">
          </div>
          <div class="form-group" id="group-select">
            <label for="group">Group</label>
            <select class="custom-select" id="group">
              <option value="default">Default</option>
            <?php
            $sqlRole = "SELECT * FROM role_group";
            $resultRole = $conn->query($sqlRole);
            while($row = $resultRole->fetch_assoc()) : ?>
              <option value="<?=$row['role_group_id']?>"><?=ucfirst($row['role_name'])?></option>
            <?php endwhile ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="submit" onclick="updateUser()" class="btn btn-primary btn-brand">Update</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>

  function getStatus(id){
    console.log(id);
  }

  $(document).ready( function () {
    $('#user-table').DataTable({
      pageLength: 10
    });
  });
  function editUser(id){
    $.ajax({
      method: "GET",
      url:"process.php?action=edit&data=user",
      data: {
        user_id: id,
      },
      success:function(res){
        var obj = JSON.parse(res);
        $('#name').text(obj.username);
        $('#user_id').val(obj.bayhost_user_id);
        $('#fullname').val(obj.fullname);
        $('#username').val(obj.username);
        $('#password').val(obj.password);
        $('#manage_user').val(obj.manage_user);
        $('#manage_package').val(obj.manage_package);
        $('#modal-edit').modal();
    }});
  }

  function updateUser() {
    let group_value = $("#group").val();
    if(group_value != 'default'){
      Swal.fire({
        title: 'Update User',
        text: "Apakah Anda yakin ingin merubah user ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, simpan!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "POST",
            url: "process.php?action=update&data=user",
            data: {
              user_id: $("#user_id").val(),
              fullname: $("#fullname").val(),
              username: $("#username").val(),
              password: $("#password").val(),
              group: $("#group").val(),
            },
            success: function(res) {
              let data = JSON.parse(res);
              if (data.status == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: ''+data.message+'',
                  showConfirmButton: false,
                  timer: 1500
                }).then((result) => {
                  if (result.dismiss === Swal.DismissReason.timer) {
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
    } else {
      alert('Pilih group terlebih dahulu!')
    }
  }

  function deleteUser(id, username){
    Swal.fire({
      title: 'Action Delete',
      text: "Are you sure to Delete user ("+username+") ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "POST",
            url: "process.php?data=user&action=delete",
            data: {
              user_id: id,
            },
            success: function(res) {
              let data = JSON.parse(res);
              if (data.status == "success") {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Success.',
                  text: ''+data.message+'',
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