<?php
session_start();
include('./include/title.php');
include('./sql/token.php');
include('./sql/connection.php');

if(empty($_SESSION['token']) || $token_id != $_SESSION['token']){
  echo "<script>";
  echo "window.location.href = './index.php'";
  echo "</script>";
  // header('Location:./login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Billing Radius | <?= $title ?></title>
  <link rel="shortcut icon" href="dist/img/logo.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php include("include/css-plugins.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  
  <!-- /.navbar -->
  <?php 
  include("include/header.php");
  include("include/main-sidebar.php"); 
  ?>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php
      include('./conf/conf.php');
    ?>
    <!-- /.content -->
  </div>

  <?php include("./include/footer.php"); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include("./include/js-plugins.php"); ?>
<script>
function goBack() {
  window.history.back();
}

  $(document).ready( function () {
    $('#alltable').DataTable({
      pageLength: 25
    });
    $('#monitor-table').DataTable({
      pageLength: 50
    });
  });
$(document).ready(function(){
  $('#check').click(function(){
    $(this).is(':checked') ? $('#secret').attr('type', 'text') : $('#secret').attr('type', 'password');
  });
});

</script>
</body>
</html>
