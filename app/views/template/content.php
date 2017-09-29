<?php
    Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
    $filename = "app/views/$m/$p.php";
?>
  
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <?php
        // jika filenya ada
        if(file_exists($filename)){
            include_once($filename);
        }
        else{ // jika tidak ada
            include_once("app/views/beranda/dashboard.php");
        }
    ?>

</div>
<!-- /.content-wrapper -->