<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Page</title>
  <link rel="shortcut icon" href="dist/img/logo.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="dist/css/bg.css">
  <?php
    include('./include/css-plugins.php');
  ?>

  <style type="text/css">
        .error{ color: red; }
        .success{ color: green; }
        .text-white { color: #fff !important;}

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box bg-transparent">
  <div class="login-logo">
    <a class="text-white" href="https://bandungcctv.com"><b>Bayhost</b>Radius</a>
  </div>
  <!-- /.login-logo -->
  <div class="card" id="form">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <div class="callout callout-danger d-none" id="loginFailed">
        <h5>Login Failed!</h5>
        <p>Username or password doesn't match</p>
      </div>
      <div>
        <div class="form-group mb-3">
          <input id="username" name="username" type="text" value="<?= isset($_COOKIE['username'])?$_COOKIE['username']:''?>" class="form-control" placeholder="Username" required <?= isset($_COOKIE['username'])?'':'autofocus'?>>
          <div class="invalid-feedback">
            Please check again your username
          </div>
        </div>
        <div class="form-group mb-3">
          <input id="password" name="password" type="password" value="<?= isset($_COOKIE['password'])?$_COOKIE['password']:''?>" class="form-control" placeholder="Password" required>
          <div class="invalid-feedback">
            Please check again your password
          </div>
        </div>
        <div class="row">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" <?= isset($_COOKIE['remember'])?'checked':''?>>
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" id="submit" class="btn btn-primary btn-block">Sign In <span class="ml-1 loader spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span></button>
          </div>
          <!-- /.col -->
        </div>
      </div>
      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<?php
  include('./include/js-plugins.php');
?>
<script>
  $("#submit").on('click', function(){
    var username = $("#username").val();
    var password = $("#password").val();
    var remember = $('#remember').is(":checked");
    $("#submit").removeClass('disabled');
    $(this).addClass('disabled');
    $(".loader").removeClass('d-none');
    // setTimeout(() => {
      $.ajax({
        method: "POST",
        url: "./process.php?action=login",
        data: {
          username: username,
          password: password,
          remember: remember,
        },
        success: function(res) {
          console.log(res)
          if (res == "success") {
            window.location.href = "index.php";
          }else{
            $("#username").addClass("is-invalid");
            $("#password").addClass("is-invalid");
            $("#loginFailed").removeClass("d-none");
            $("#submit").removeClass('disabled');
            $(".loader").addClass("d-none");
          }
        }
      })
    // }, 3000);
  });

  $("#form").keyup(function(e){
    if(e.keyCode === 13) {
      var username = $("#username").val();
      var password = $("#password").val();
      var remember = $('#remember').is(":checked");
      $("#submit").removeClass('disabled');
      $("#submit").addClass('disabled');
      $(".loader").removeClass('d-none');
      // setTimeout(() => {
        $.ajax({
          method: "POST",
          url: "./process.php?action=login",
          data: {
            username: username,
            password: password,
            remember: remember,
          },
          success: function(res) {
            if (res == "success") {
              window.location.href = "index.php";
            }else{
              $("#username").addClass("is-invalid");
              $("#password").addClass("is-invalid");
              $("#loginFailed").removeClass("d-none");
              $("#submit").removeClass('disabled');
              $(".loader").addClass("d-none");
            }
          }
        })
      // }, 3000);
    }
    
  })
</script>