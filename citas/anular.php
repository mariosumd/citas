<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Citas para el médico</title>
    </head>
    <body><?php
        require '../comunes/auxiliar.php'; 
        define("UN_DIA", 3600 * 24);
        
        conectar();
        
        if (!isset($_POST['id_usuario'])) {
            if (!isset($_GET['id_usuario'])) {
            header("Location: ../index.php");
            return;
            } else {
                $id_usuario = $_GET['id_usuario'];
            }
        } else {
            $id_usuario = $_POST['id_usuario'];
            $fecha = $_POST['fecha'];
            $hora  = $_POST['hora'];
            anular_cita($id_usuario, $fecha, $hora); ?>
            <h3>Cita correctamente anulada.</h3><?php
            volver();
            return;
        }
        
        comprobar_usuario_id($id_usuario);
        if (!comprobar_tiene_cita($id_usuario)) {
            header("Location: ../index.php");
            return;
        } 
        
        $res = pg_query_params("select to_char(fecha, 'DD-MM-YYYY') 
                                       as fecha_format, hora 
                                       from citas where id_usuario = $1", 
                                array($id_usuario)); 
        $fila  = pg_fetch_assoc($res, 0);
        $fecha = $fila['fecha_format'];
        $hora  = $fila['hora']; 
        $hora  = date('H:i', strtotime($hora)); ?>
        
        <h4>¿Está seguro de querer anular?</h4>
        <h4>Cita día <?= date("d-m-Y", strtotime($fecha)) ?> a las <?= $hora ?></h4>
        <form action="anular.php" method="post">
            <input type="hidden" name="id_usuario" value=<?= $id_usuario ?> />
            <input type="hidden" name="fecha" value=<?= $fecha ?> />
            <input type="hidden" name="hora" value=<?= $hora ?> />
            <input type="submit" value="Sí" />
            <a href="../index.php"><input type="button" value="No" /></a>
        </form>
    </body>
</html>