<?php
  $sessi_user = $_SESSION['user']['username'];
  $sqlSessi = "SELECT role,fullname FROM bayhost_users WHERE username = '".$sessi_user."'";
  $resSessi = $conn->query($sqlSessi);
  $responseSessi = $resSessi->fetch_assoc();
  $role = $responseSessi['role'];
  $fullname = $responseSessi['fullname'];
?>
<aside class="main-sidebar sidebar-dark-brand elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="dist/img/logo-df.png" style="width: 15%; height: auto;" alt="Freeradius Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="ml-1 brand-text font-weight-light" style="font-size: 16px;"><b>Freeradius</b> Manager</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-2 pb-2 mb-2 d-flex">
          <div class="info px-3">
            <a href="#" class="d-block text-sm">Welcome, <?= ucwords($fullname) ?></a>
          </div>
        </div>
      <!-- Sidebar Menu -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header pt-3 pb-1">MENU</li>
          <li class="nav-item">
            <a href="admin.php?task=dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-header pt-3 pb-1">RADIUS</li>
          <li class="nav-item">
            <a href="admin.php?task=router-nas" class="nav-link <?= ($_GET['task'] == 'add-nas' || $_GET['task'] == 'edit-nas') ? 'active' : '' ?>">
              <i class="nav-icon fa fa-hdd"></i>
              <p>
                Router Nas
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="admin.php?task=voucher-list" class="nav-link <?= ($_GET['task'] == 'add-voucher' || $_GET['task'] == 'edit-voucher') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage Voucher
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="admin.php?task=package-list" class="nav-link  <?= ($_GET['task'] == 'add-package' || $_GET['task'] == 'edit-package') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>
                Manage Paket
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="admin.php?task=user-active" class="nav-link">
              <i class="nav-icon fas fa-desktop"></i>
              <p>
                Monitor Online
              </p>
            </a>
          </li>
          <li class="nav-header pt-3 pb-1">ADVANCED</li>
          <li class="nav-item">
            <a href="admin.php?task=assign-package" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Atur Bonus Paket
              </p>
            </a>
          </li>
          <li class="nav-header pt-3 pb-1">MISC</li>
          <li class="nav-item">
            <a href="admin.php?task=report" class="nav-link <?= ($_GET['task'] == 'report-data') ? 'active' : '' ?>">
              <i class="nav-icon far fa-file-alt"></i>
              <p>
                Laporan
              </p>
            </a>
          </li>
          <li class="nav-item <?= ($sessi_user != 'admin') ? 'd-none' : '' ?>">
            <a href="admin.php?task=preference" class="nav-link <?= ($_GET['task'] == 'add-user') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Preferences
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>