<?php
   # include_once("./dbconfig.php");
   # validate_user('surya');
?>
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand 0" href="./index.php">
        <img src="./assets/img/brand/imasofttech.png" class="navbar-brand-img" alt="...">
      </a>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="./index.php">
                <img src="./assets/img/brand/blue.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->


        <h6 class="navbar-heading text-muted">Patch Management</h6>
        <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-laptop text-red"></i> Patching
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <a href="./patching.php?type=QUEUED" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Queued</span>
            </a>
            <a href="./patching.php?type=RUNNING" class="dropdown-item">
              <i class="ni ni-support-16 text-yellow"></i>
              <span>Running</span>
            </a>
            <a href="./patching.php?type=COMPLETED" class="dropdown-item">
              <i class="ni ni-support-16 text-green"></i>
              <span>Completed</span>
            </a>
            <a href="./patching.php?type=CANCELLED" class="dropdown-item">
              <i class="ni ni-support-16 text-blue"></i>
              <span>Cancelled</span>
            </a>
            <a href="./patching.php?type=FAILED" class="dropdown-item">
              <i class="ni ni-support-16 text-red"></i>
              <span>Failed</span>
            </a>    
            <a href="./patching.php?type=ALL" class="dropdown-item">
              <i class="ni ni-support-16 text-grey"></i>
              <span>All</span>
            </a>                                
          </div>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-notification-70 text-red"></i> Notification
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <a href="notification.php?type=SCHEDULED" class="dropdown-item">
              <i class="ni ni-send text-blue"></i>
              <span>Scheduled</span>
            </a>
            <a href="notification.php?type=PENDING" class="dropdown-item">
              <i class="ni ni-send text-yellow"></i>
              <span>Pending</span>
            </a>
            <a href="notification.php?type=NO_RESPONSE" class="dropdown-item">
              <i class="ni ni-send text-red"></i>
              <span>No Response</span>
            </a>
            <a href="notification.php?type=APPROVED" class="dropdown-item">
              <i class="ni ni-send text-green"></i>
              <span>Approved</span>
            </a>
          </div>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-watch-time text-red"></i> Schedule
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <a href="schedule.php" class="dropdown-item">
              <i class="ni ni-check-bold text-green"></i>
              <span>Normal (Linux)</span>
            </a>
            <a href="schedule_custom.php" class="dropdown-item">
              <i class="ni ni-check-bold text-blue"></i>
              <span> Custom </span>
            </a>
          </div>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-laptop text-red"></i> Host
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <a href="register.php" class="dropdown-item">
              <i class="ni ni-air-baloon text-green"></i>
              <span>Register</span>
            </a>
            <a href="display.php?host=ALL" class="dropdown-item">
              <i class="ni ni-air-baloon text-blue"></i>
              <span> Show </span>
            </a>
          </div>
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-chart-bar-32 text-red"></i> Reports
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <a href="reports.php" class="dropdown-item">
              <i class="ni ni-fat-add"></i>
              <span>Graph</span>
            </a>
            <a href="dump.php" class="dropdown-item">
              <i class="ni ni-fat-add"></i>
              <span> CSV </span>
            </a>
          </div>
        </li>
          <li class="nav-item">
            <a class="nav-link" href="./admin.php">
              <i class="ni ni-single-02 text-yellow"></i> Admin User 
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./patching_dashboard.php">
              <i class="ni ni-chart-bar-32 text-red"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./config.php">
              <i class="ni ni-settings-gear-65 text-green"></i> Config
            </a>
          </li>
        </ul>
        <!-- Divider -->
        <hr class="my-3">
      </div>
    </div>
  </nav>