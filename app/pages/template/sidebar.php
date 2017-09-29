<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>
	<!-- Sidebar -->
  	<aside class="main-sidebar">
    	<!-- sidebar: style can be found in sidebar.less -->
    	<section class="sidebar">
      		<!-- Sidebar user panel -->
	      	<div class="user-panel">
	        	<div class="pull-left image">
	          		<img src="<?= base_url."assets/dist/img/user2-160x160.jpg"; ?>" class="img-circle" alt="User Image">
	        	</div>
	        	<div class="pull-left info">
	          		<p><?= $sess_nama ?></p>
	          		<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	        	</div>
	      	</div>
	      
	      	<!-- search form -->
	      	<form action="#" method="get" class="sidebar-form">
	        	<div class="input-group">
	          		<input type="text" name="q" class="form-control" placeholder="Search...">
	              	<span class="input-group-btn">
	                	<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
	              	</span>
	        	</div>
	      	</form>
	      	<!-- /.search form -->
	      
	      	<!-- sidebar menu: : style can be found in sidebar.less -->
	      	<ul class="sidebar-menu">
	        	<li class="header">MENU NAVIGASI</li>
	        	<!-- menu beranda -->
	        	<li <?php if($m=="beranda" || !$m) echo "class='active'" ?>>
	          		<a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> <span>Beranda</span></a>
	        	</li>

	        	<!-- menu transaksi sewa -->
	        	<li <?php if($m=="sewa") echo "class='active'" ?>>
	          		<a href="<?= base_url."index.php?m=sewa&p=list"; ?>"><i class="fa fa-dashboard"></i> <span>Transaksi Sewa</span></a>
	        	</li>

	        	<!-- menu keuangan -->
	        	<li <?php if($m=="keuangan") echo "class='active'" ?>>
	          		<a href="<?= base_url."index.php?m=keuangan&p=list"; ?>"><i class="fa fa-dashboard"></i> <span>Keuangan</span></a>
	        	</li>

	        	<!-- menu data master -->
	        	<li class="treeview <?php if($m=="member" || $m=="parkir" || $m=="harga") echo "active" ?>">
	          		<a href="#">
	            		<i class="fa fa-pie-chart"></i>
	            		<span>Data Master</span>
	            		<span class="pull-right-container">
	              			<i class="fa fa-angle-left pull-right"></i>
	            		</span>
	          		</a>
	          		<!-- Sub Menu -->
	          		<ul class="treeview-menu">
	            		<li <?php if($m=="member") echo "class='active'" ?>><a href="<?= base_url."index.php?m=member&p=list"; ?>"><i class="fa fa-circle-o"></i> Member</a></li>
	            		<li <?php if($m=="parkir") echo "class='active'" ?>><a href="<?= base_url."index.php?m=parkir&p=list"; ?>"><i class="fa fa-circle-o"></i> Parkir</a></li>
	            		<li <?php if($m=="harga") echo "class='active'" ?>><a href="<?= base_url."index.php?m=harga&p=list"; ?>"><i class="fa fa-circle-o"></i> Harga Sewa</a></li>
	          		</ul>
	        	</li>

	        	<?php
	        		if(strtolower($sess_lvl) == "superadmin"){ //jika belum arahkan ke login
						?>
						<!-- menu data admin -->
			        	<li class="treeview <?php if($m=="admin" || $p=="log_admin") echo "active" ?>">
			          		<a href="#">
			            		<i class="fa fa-pie-chart"></i>
			            		<span>Data Admin</span>
			            		<span class="pull-right-container">
			              			<i class="fa fa-angle-left pull-right"></i>
			            		</span>
			          		</a>
			          		<!-- Sub Menu -->
			          		<ul class="treeview-menu">
			            		<li <?php if($m=="admin" & $p=="list") echo "class='active'" ?>><a href="<?= base_url."index.php?m=admin&p=list"; ?>"><i class="fa fa-circle-o"></i> Admin</a></li>
			            		<li <?php if($m=="admin" & $p=="log_admin") echo "class='active'" ?>><a href="<?= base_url."index.php?m=admin&p=log_admin"; ?>"><i class="fa fa-circle-o"></i> Log Admin</a></li>
			          		</ul>
			        	</li>
						<?php	
					}
	        	?>
		</section>
	    <!-- /.sidebar -->
	</aside>
	<!-- End Sidebar -->