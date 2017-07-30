<?php
    session_start();

    include_once("function/helper.php");
    include_once("function/koneksi.php");

    //var session admin
    $sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
    $sess_auth = isset($_SESSION['sess_auth']) ? $_SESSION['sess_auth'] : false; 
    $sess_kodeAkses = isset($_SESSION['sess_kodeAkses']) ? $_SESSION['sess_kodeAkses'] : false;
    $sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;
    $pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
    $set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

    //unset session
    unset($_SESSION['pesanError']);
    unset($_SESSION['set_value']);

    $username = $usernameError = $hasil = "";

    if($pesanError){
        $usernameError = $pesanError['usernameError'];
        $hasil = $pesanError['hasil'];
    }

    if($set_value){
        $username = $set_value['username'];
    }

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
          <form action="<?= base_url."pages/login/proses_login.php"; ?>" method="post" id="formLogin">
              <div class="form-group has-feedback <?php if(!empty($usernameError)) echo 'has-error' ?>">
                <input class="form-control" type="text" name="username" id="username" placeholder="Username" value="<?= $username ?>">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <span class="help-block"><?= $usernameError ?></span>
              </div>
              <div class="form-group has-feedback <?php if(!empty($usernameError)) echo 'has-error' ?>">
                <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span class="help-block"><?= $usernameError ?></span>
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
    
    <!-- JavaScript -->
      <?php include_once("pages/template/javascript.php"); ?>
      <!-- custom js -->
      
      <!-- end js --> 
  </body>
</html>