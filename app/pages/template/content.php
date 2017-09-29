<?php
    Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>
    <!-- Content -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php
            $filename = "pages/$m/$p.php";
            // $notif = true;
            //$notif = $pesan;
            
            if(file_exists($filename)){ //akses menu yg dingingkan
                $notif = false;
                include_once($filename);
            }
            else{ // jika halaman tidak ada
                if(!$m && !$p){ //jika menu dan page tidak ada
                    $notif = false;
                    include_once("pages/beranda/beranda.php");
                }
                else{ //jika req. menu dan page salah
                    $notif = true;
                    include_once("pages/beranda/beranda.php"); //tampilkan beranda
                }
            }
        ?>
  	</div>
  	<!-- /.content-wrapper -->
    <!-- End Content -->