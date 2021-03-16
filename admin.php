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
      pageLength: 10
    });
    // $('.openModal').on('click', function(){
    //   var user_id = $(this).attr('data-id');
    //   console.log(user_id);
    //   $.ajax({
    //     method: "GET",
    //     url:"./modal/modal_user.php?id="+user_id,
    //     cache:false,
    //     success:function(result){
    //       $(".modal-content").html(result);
    //   }});
    // });
  });
$(document).ready(function(){
  $('#check').click(function(){
    $(this).is(':checked') ? $('#secret').attr('type', 'text') : $('#secret').attr('type', 'password');
  });
  $('#report-table').DataTable({
    pageLength: 25,
  });
});

</script>
</body>
</html>
