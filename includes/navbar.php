<header class="app-header bg-light border-0 navbar">
<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
<span class="navbar-toggler-icon"></span>
</button>
<button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
<span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="index.php?page=dashboard"><b class="brand-blue">Attend</b><span class="brand-light">Ease</span></a>
<ul class="nav navbar-nav ml-auto">
<li class="nav-item d-md-down-none">
<a class="nav-link" href="#"><i class="fa fa-bell-o"></i></a>
</li>
<li class="nav-item dropdown">
<a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
<img class="img-avatar" src="assets/admin.jpg" alt="Admin"/>
</a>
<div class="dropdown-menu dropdown-menu-right">
<div class="dropdown-header"><strong><?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Admin User'; ?></strong></div>
<a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
<a class="dropdown-item" href="logout.php"><i class="fa fa-lock"></i> Logout</a>
</div>
</li>
</ul>
<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
<span class="navbar-toggler-icon"></span>
</button>
</header>
