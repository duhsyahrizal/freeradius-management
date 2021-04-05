<?php
  $start_date = $_GET['start_date'];
  $end_date = $_GET['end_date'];
  $queryTotal = "SELECT SUM(price) AS total FROM bill_report WHERE payment != 2";
  $total = $conn->query($queryTotal);
  $total = $total->fetch_assoc();
  $total = $total['total'];

  $queryTotalVoucher = "SELECT COUNT(id) AS jumlah FROM bill_report";
  $jumlah = $conn->query($queryTotalVoucher);
  $jumlah = $jumlah->fetch_assoc();
  $jumlah = $jumlah['jumlah'];

  $queryRefill = "SELECT COUNT(id) AS jumlah FROM bill_report WHERE `type` = 'Refill Voucher'";
  $refill = $conn->query($queryRefill);
  $refill = $refill->fetch_assoc();
  $refill = $refill['jumlah'];

  $queryVBaru = "SELECT COUNT(id) AS jumlah FROM bill_report WHERE payment = 2";
  $vbaru = $conn->query($queryVBaru);
  $vbaru = $vbaru->fetch_assoc();
  $vbaru = $vbaru['jumlah'];
?>
<div class="container-fluid">
  <div class="row px-2">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <h1 class="m-0 text-dark"><?=$title?></h1>
    </div>
  </div>
</div>

<!-- /.Main content -->
<section class="content px-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="callout callout-info">
          <h5>Total Harga Voucher</h5>

          <p>Rp. <?=number_format($total, 0, ",", ".")?> <br></p>
        </div>
      </div>
      <div class="col">
        <div class="callout callout-warning">
          <h5>Total Voucher Gratis</h5>

          <p><?=$vbaru?> Voucher</p>
        </div>
      </div>
      <div class="col">
        <div class="callout callout-success">
          <h5>Total Voucher Refill</h5>

          <p><?=$refill?> Voucher</p>
        </div>
      </div>
      <div class="col">
        <div class="callout callout-danger">
          <h5>Total Semua Voucher</h5>

          <p><?=$jumlah?> Voucher</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content px-2">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
      <div class="card card-primary card-outline">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="far fa-file-alt mr-1"></i> Laporan dari tanggal <strong><?= $start_date ?></strong> s/d <strong><?= $end_date ?></strong></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <!-- <a href="admin.php?token=<?=$_SESSION['token']?>&page=add-router" class="btn btn-primary btn-sm"><i class="fa fa-plus mr-1"></i> Add Router</a> -->
            </div>
            <!-- /.card-tools -->
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered text-sm" id="alltable">
                <thead class="bg-brand">
                  <tr>
                  <th style="width: 5%;" class="text-center" scope="col">No</th>
                  <th scope="col">Username</th>
                  <th scope="col">Metode</th>
                  <th scope="col">Deskripsi</th>
                  <th scope="col">Paket</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Dibuat</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $sql = "SELECT 
                  bill_report.id,
                  bill_report.username,
                  bill_report.payment,
                  bill_report.billing_package_id,
                  bill_report.price,
                  bill_report.type AS report_type,
                  bill_report.description AS report_desc,
                  bill_report.created_by,
                  billing_package.name AS package_name,
                  payment_method.id,
                  payment_method.name AS payment_name,
                  payment_method.description
                  FROM bill_report
                  JOIN payment_method ON bill_report.payment = payment_method.id
                  JOIN billing_package ON bill_report.billing_package_id = billing_package.id
                  WHERE created_at BETWEEN '".$start_date."' AND '".$end_date."'";
                  $resultReport = $conn->query($sql); 
                  $num = 0;
                  while($row=$resultReport->fetch_assoc()){
                    $num=$num+1;
                  ?>
                  <tr>
                  <td><?= $num ?></td>
                  <td><?= $row['username'] ?></td>
                  <td><span class="py-1 px-1 badge badge-<?= ($row['payment_name'] == 'Gratis') ? 'info' : 'primary bg-brand' ?>"><?= $row['payment_name'] ?></span></td>
                  <td><span class="badge <?=($row['report_type'] == 'Voucher Baru') ? 'badge-success' : 'badge-primary bg-brand' ?>"><?=$row['report_type']?></span> - <?= ($row['payment_name'] == 'Cash') ? $row['description'] : $row['report_desc'] ?></td>
                  <td><?= $row['package_name'] ?></td>
                  <td>Rp. <?= number_format($row['price'], 0, ",", ".") ?></td>
                  <td><?= ucfirst($row['created_by']) ?></td>
                  </tr>
                  <?php 
                    }
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

<script>

</script>
    