<?php
    session_start();

    include_once("function/helper.php");
    include_once("function/koneksi.php");

    //var session admin
    $sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
    $sess_auth = isset($_SESSION['sess_auth']) ? $_SESSION['sess_auth'] : false; 
    $sess_kodeAkses = isset($_SESSION['sess_kodeAkses']) ? $_SESSION['sess_kodeAkses'] : false;
    $sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;

    if($sess_login){ //jika sudah terdeteksi login
      header("Location: ".base_url); //arahkan ke index
        die();
    }

    reset_session();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login | Sistem Informasi Sewa Garasi Cesara</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- CSS -->
            <script src="<?= base_url."assets/plugins/jQuery/jquery-2.2.3.min.js"; ?>"></script>
            <?php include_once("pages/template/css.php"); ?>
            <style type="text/css">
              .loader{
                border: 5px solid #f3f3f3;
                border-radius: 50%;
                border-top: 5px solid #3498db;
                width: 35px;
                height: 35px;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
              }
              @-webkit-keyframes spin{
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
              }
              @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
              }
            </style>
        <!-- end CSS -->
      </head>
      <body class="hold-transition login-page">
          <div class="login-box">
              <div class="login-logo">
                  <a href="<?= base_url; ?>"><b>Login</b>SISGAR</a>
              </div>
        
        <!-- /.login-logo -->
        <div class="login-box-body">
          <p class="login-box-msg">Silahkan Login</p>
          <hr>
          <form action="" method="post" id="formLogin">
              <div class="form-group has-feedback">
                <input class="form-control" type="text" name="username" id="username" placeholder="Username">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-offset-8 col-xs-4">
                    <button class="btn btn-primary btn-block btn-flat" type="submit" name="submitLogin">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
          </form>

        <hr>
      </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
    <!-- loader -->
    <center>
        <span id="txtLoading" style="display: none">Loading...</span>
        <div id="loading" class="loader" style="display: none"></div>
    </center>

    <!-- Modals Auth -->
    <div class="modal fade" id="modalAuth" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h4>Konfirmasi Admin</h4></div>
          <!-- form konfirmasi token -->
          <form id="formAuth" method="post" action="">
            <div class="modal-body">
              <!-- kode token -->
              <div class="form-group">
                <label for="kodeToken">Kode Token</label>
                <input class="form-control" type="password" name="kodeToken" id="kodeToken">
              </div>
            </div>
            <div class="modal-footer">
                <!-- button -->
              <div class="form-group text-right">
                <button class="btn btn-primary" type="submit" name="submitAuth" id="submitAuth">Konfirmasi</button>
                <button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
              </div>
              </div>
            </form>
        </div>
      </div>
    </div>

    <!-- JavaScript -->
      <?php include_once("pages/template/javascript.php"); ?>
      <!-- custom js -->
      <script type="text/javascript">
          $(document).ready(function(){ 
            //var base_url
            var base_url = "<?php print base_url; ?>";

            //ajax login
            $('#formLogin').submit(function(){
              var username = $.trim($('#username').val());
              var password = $.trim($('#password').val());
              var cek = true;
              
              $.ajax({
                url: base_url+"pages/login/proses_login.php",
                type: "post",
                data: 'username='+username+'&password='+password+'&cekSubmit='+cek,
                beforeSend: function(){
                  $('#txtLoading').show();
                  $('#loading').show(); //tampilkan loading sebelum req.
                },
                success: function(hasil){ //jika sukses
                  $('#txtLoading').hide();
                  $('#loading').hide(); //jika req selesai
                  // alert(hasil);
                  if(hasil=='empty'){ //jika kosong
                    swal("Pesan Error", "Username dan Password Tidak Boleh Kosong", "warning");
                    return false;
                  }
                  else if(hasil=='wrong' || hasil=="nonaktif"){ //jika user dan pass salah
                    swal("Pesan Error", "Username atau Password Salah", "error");
                    return false;
                  }
                  else if(hasil=='error_db'){ //jika db error
                    swal("Pesan Error", "Koneksi Ke Database Error", "error");
                    return false;
                  }
                  else if(hasil=='fail_send_email'){ //jika kirim email error
                    swal("Pesan Error", "Kirim Email Gagal, Silahkan Coba Lagi", "error");
                    return false;
                  }
                  else if(hasil=='auth'){ //jika superadmin login
                    //tampilkan modals auth kode token
                    $("#modalAuth").modal();
                    return false;
                  }
                  else{ //jika sukses
                    document.location=base_url;
                    // swal(hasil);
                  }
              }
            });
            return false; 
          });

        //ajax auth
        $('#formAuth').submit(function(){
          var kodeToken = $.trim($('#kodeToken').val());
          var cekAuth = true;
          $.ajax({
            url: base_url+"pages/login/proses_auth.php",
            type: "post",
            data: 'kodeToken='+kodeToken+'&cekAuth='+cekAuth,
            success: function(hasilAuth){
              // alert(hasilAuth);
              if(hasilAuth=='empty'){
                swal("Pesan Error", "Kode Token Tidak Boleh Kosong", "warning");
                // $("#modalAuth").modal("hide");
                return false;
              }
              else if(hasilAuth=='false_token'){
                swal("Pesan Error", "Kode Token Salah", "error");
                $("#modalAuth").modal("hide");
                $('#kodeToken').val("");
              }
              else if(hasilAuth=='fail'){
                swal("Pesan Error", "Kode Token Salah", "error");
                $("#modalAuth").modal("hide");
                $('#kodeToken').val("");
              }
              else{ //jika sukses
                document.location=base_url;
              }
            }
          });
          return false;
        });

        //modal auth di tutup
        $('#modalAuth').on('hidden.bs.modal', function () {
          $.ajax({
            url: base_url+"function/function_action.php",
            data: {action: 'closeModal'},
            type: 'post',
            success: function(hasil){
              if(hasil=='success'){
                swal("Semua Session Telah Diset ulang");
              }
            }
          }); 
        });

      });
      </script>
    <!-- end js --> 
  </body>
</html>