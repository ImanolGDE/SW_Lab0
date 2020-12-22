<?php session_start();
if(isset($_SESSION['Email'])){
  header('location: Layout.php');
}
?>
<html>

<head>
  <?php include '../html/Head.html'?>
  <style>
		.table_flogin {
			margin: auto;
      text-align: center;
		}
		sup {
			color: red;
		}
    h2 {
        color: darkblue;
    }
    .error {
        color: darkred;
    }
    .success {
        color: darkgreen;
    }
    
  </style>
</head>

<body>
  <?php include '../php/Menus.php' ?>
  <?php include '../php/DbConfig.php' ?>
  <section class="main" id="s1">
    <div>
      <form id="flogin" name="flogin" method="POST" enctype="multipart/form-data" action="LogIn.php">
        <table class="table_flogin">
          <tr><th><h2>Iniciar sesion</h2><br/></th></tr>
          <tr><td>Dirección de correo<sup>*</sup> <input type="email" size="65" id="dirCorreo" name="dirCorreo"></td></tr>
          <tr><td>Contraseña<sup>*</sup> <input type="password" size="75" id="pass1" name="pass1"></td></tr>
          <tr><td><div id="buttons"><input type="submit" id="submit" value="Enviar"> <input type="reset" id="reset" value="Limpiar"></div></td></tr>
        </table>
        <a href="ForgotPassword.php">Olvidé la contraseña</a>
      </form>
    </div>

    <div>
      <?php
        
        if(isset($_REQUEST['dirCorreo'])) {
          $email = $_REQUEST['dirCorreo'];
          $pass1 = $_REQUEST['pass1'];
          $mysqli = mysqli_connect($server, $user, $pass, $basededatos);
          if(!$mysqli){
              die("Fallo al conectar con Mysql: ".mysqli_connect_error());
              echo "<span><a href='javascript:history.back()'>Volver</a></span>";
          }
          $sql = "SELECT * FROM usuarios WHERE email=\"".$email."\";";
          $resultado = mysqli_query($mysqli, $sql, MYSQLI_USE_RESULT);
          if(!$resultado){
            die("Error: ".mysqli_error($mysqli));
            echo "<span><a href='javascript:history.back()'>Volver</a></span>";
          }
          $row = mysqli_fetch_array($resultado);
          if(!empty($row) && $row['estado'] =='activar' && $row['email']==$email && hash_equals($row['pass'], crypt($pass1, $row['pass']))){
            // echo "<p class=\"success\">Inicio de sesion realizado correctamente<p><br/>";
            // echo "<span><a href='Layout.php'>Ir al inicio</a></span>";
            $_SESSION['Rol'] = $row['Rol'];
            $_SESSION['Email'] = $row['email'];
            $_SESSION['Image'] = $row['imagen'];
            echo "<script> alert(\"¡Bienvenido!\"); document.location.href='Layout.php'; </script>";
					} else {
            if($row['estado'] != 'activar'){
              echo "<p class=\"error\">Cuenta bloqueada!<p><br/>";
            }else{
            echo "<p class=\"error\">Usuario o contraseña incorrectos!<p><br/>";}
            // echo "<span><a href=\"javascript:history.back()\">Volver</a></span>";
          }
        }
      ?>
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>

</html>