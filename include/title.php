<?php
if(isset($_GET['task'])){
    $task = $_GET['task'];
    switch($task){
      case 'dashboard':
        $title = ucfirst($task);
        break;
      case 'voucher-list':
        $title = 'Voucher';
        break;
      case 'router-nas':
        $title = 'Router Nas';
        break;
      case 'edit-nas':
        $title = 'Edit Nas';
        break;
      case 'add-nas':
        $title = 'New Nas';
        break;
      case 'add-voucher':
        $title = 'Buat Voucher baru';
        break;
      case 'edit-voucher':
        $title = 'Ubah Voucher';
        break;
      case 'package-list':
        $title = 'Package';
        break;
      case 'add-package':
        $title = 'Buat Paket baru';
        break;
      case 'edit-package':
        $title = 'Ubah Paket';
        break;
      case 'assign-package':
        $title = 'Bonus Paket';
        break;
      case 'user-active':
        $title = 'Monitor Online';
        break;
      case 'system':
        $title = ucfirst($task);
        break;
      case 'manage-user':
        $title = 'Manage User';
        break;
      case 'preference':
        $title = ucfirst($task);
        break;
      case 'report':
        $title = 'Laporan';
        break;
      case 'report-data':
        $title = 'Data Laporan';
        break;
    }
}
?>