<?php
include('../sql/connection.php');

$queryPackage = "SELECT * FROM billing_package";
$package = $conn->query($queryPackage);
?>
<div class="form-group paket-group">
    <label for="profile">Pilih Paket Billing</label>
    <div class="input-group mb-3">
        <select class="custom-select" id="profile" name="profile[]">
        <?php foreach($package as $row) : ?>
            <option value="<?=$row['id']?>"><?=$row['package_name']?></option>
        <?php endforeach ?>
        </select>
        <div class="input-group-append">
            <button class="btn btn-danger hapus-paket" type="button"><i class="fas fa-minus mr-1"></i> Hapus</button>
        </div>
    </div>
</div>