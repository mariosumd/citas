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
            dar_cita($id_usuario, $fecha, $hora); ?>
            <h3>Cita correctamente reservada.</h3><?php
            volver();
            return;
        }
        
        comprobar_usuario_id($id_usuario);
        if (comprobar_tiene_cita($id_usuario)) { ?>
            <h3>Ya tiene una cita. Cancélela primero.</h3><?php
            volver();
            return;
        }
        
        $hora  = "";
        $fecha = date('Y-m-d');
        
        for ($i = 0; $i < 10; $i++) {
            $fecha = date($fecha, strtotime("+$i days"));
            for ($j = 10; $j < 22; $j++) {
                $res = pg_query_params("select * from citas where fecha = $1 and hora = $j", array($fecha));
                
                if (pg_num_rows($res) == 0) {
                    $hora = $j;
                    break;
                }
            }
        }
        
        if ($hora == "") { ?>
            <h3>No ha sido posible darle cita.</h3><?php
            volver();
        } else { ?>
            <h4>Cita día <?= date("d-m-Y", strtotime($fecha)) ?> a las <?= $hora ?>:00</h4>
            <form action="confirmar.php" method="post">
                <input type="hidden" name="id_usuario" value=<?= $id_usuario ?> />
                <input type="hidden" name="fecha" value=<?= $fecha ?> />
                <input type="hidden" name="hora" value=<?= $hora ?> />
                <input type="submit" value="Sí" />
                <a href="../index.php"><input type="button" value="No" /></a>
            </form><?php
        } ?>
    </body>
</html>