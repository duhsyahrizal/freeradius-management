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
            <h3 class="card-title"><i class="fas fa-user-check mr-1"></i> <?= $title ?></h3>
            <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered dt-responsive nowrap text-sm" style="width:100%" id="alltable">
                <thead>
                    <tr class="bg-brand">
                    <th scope="col">No</th>
                    <th scope="col">Username</th>
                    <th scope="col">IP Address</th>
                    <th scope="col">Durasi Online</th>
                    <th scope="col">Download</th>
                    <th scope="col">Upload</th>
                    <th scope="col">Status</th>
                    <!-- <th scope="col">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php
                    $num = 0;
                    $queryAct = "SELECT * FROM radacct WHERE acctstoptime IS NULL GROUP BY acctsessionid ORDER BY username ASC";
                    $resultAct = $conn->query($queryAct);
                    while($row=$resultAct->fetch_assoc()) :
                ?>
                    <tr>
                    <td style="width: 5%;" class="text-center"><?= $num=$num+1 ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['framedipaddress'] ?></td>
                    <td><?= secondsToWords($row['acctsessiontime']) ?></td>
                    <td><?= convert_bytes($row['acctoutputoctets'], 'M') ?> MB</td>
                    <td><?= convert_bytes($row['acctinputoctets'], 'M') ?> MB</td>
                    <td><small><i class="fa fa-circle text-success"></i></small> Online</td>
                    <!-- <td class="py-2"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><i class="far fa-eye mr-1"> </i>Show</button></td> -->
                    </tr>
                    <?php 
                      endwhile;
                    ?>
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                  
                </div>
              </div>
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
<script>
  
</script>