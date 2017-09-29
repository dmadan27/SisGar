<?php
    Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url."assets/gambar/admin/".$sess_foto; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $sess_nama ?></p>
                <!-- Status -->
                <a href="javascript:;">
                    <i class="fa fa-circle text-success"></i> 
                    Online
                </a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Menu Navigasi</li>
            <?php echo cetak_menu($sess_akses); ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>

</aside>
