<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Login</title>
  </head>
  <body><?php
    
    require '../comunes/auxiliar.php';
    
    if (isset($_POST['nombre'], $_POST['password'])):
      $nombre = trim($_POST['nombre']);
      $password = trim($_POST['password']);
      $con = conectar();
      $res = pg_query($con, "select id_usuario
                               from usuarios
                              where nombre = '$nombre' and
                                    password = md5('$password')");
      if (pg_num_rows($res) > 0):
        $fila = pg_fetch_assoc($res, 0);
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        header("Location: /citas");
      else: ?>
        <h3>Error: contraseña incorrecta</h3><?php
      endif;
    endif; ?>

    <form action="login.php" method="post">
      <label for="nombre">Nombre:</label>
      <input type="text" name="nombre" /><br/>
      <label for="password">Contraseña:</label>
      <input type="password" name="password" /><br/>
      <input type="submit" value="Entrar" />
    </form>
  </body>
</html>
  
    