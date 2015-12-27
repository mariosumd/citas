<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Citas para el médico</title>
    </head>
    <body><?php       
        require './comunes/auxiliar.php';

        conectar();
        
        if (isset($_GET['id_usuario'])) {
            $listado_id = $_GET['id_usuario'];
            comprobar_id_usuario($id_usuario);
        }
        
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: ./usuarios/login.php");
            return;
        } else {
            $id_usuario = $_SESSION['id_usuario'];
        }

        $res = pg_query_params("select *
                                 from usuarios
                                where id_usuario = $1", array($id_usuario));

        $fila = pg_fetch_assoc($res, 0);
        $nombre = $fila['nombre']; ?>

        <form action="./usuarios/logout.php" method="post">
            <p align="right">
                Usuario: <strong><?= $nombre ?></strong>
                <input type="submit" value="Salir" />
            </p>
        </form>
        <hr/><?php
        
        if (comprobar_tiene_cita($id_usuario)) { 
            $res = pg_query_params("select to_char(fecha, 'DD-MM-YYYY') as fecha_format, 
                                           hora from citas where id_usuario = $1", 
                                    array($id_usuario)); 
            $fila  = pg_fetch_assoc($res, 0);
            $fecha = $fila['fecha_format'];
            $hora  = $fila['hora']; 
            $hora  = date('H:i', strtotime($hora)); ?>
        
            <h3>Tiene cita el día <?= $fecha ?> a las <?= $hora ?></h3>
            <form action="citas/anular.php" method="get">
                <input type="hidden" name="id_usuario" value=<?= $id_usuario ?> />
                <input type="submit" value="Anular Cita" />
            </form>
        <?php
        } else { ?>
            <form action="citas/confirmar.php" method="get">
                <input type="hidden" name="id_usuario" value=<?= $id_usuario ?> />
                <input type="submit" value="Reservar Cita" />
            </form><?php
        }
        ?>
        <br />
        <form action="index.php" method="get">
            <input type="hidden" name="listado_id" value=<?= $id_usuario ?> />
            <input type="submit" value="Listado" />
        </form> <?php
        
        if (isset($_GET['listado_id'])) {
            $id_usuario = $_GET['listado_id'];
            $res = pg_query_params("select fecha, hora from citas where id_usuario = $1", array($id_usuario));

            if (pg_num_rows($res) > 0) {
                for ($i = 0; $i < pg_num_rows($res); $i++) {
                    $fila = pg_fetch_assoc($res, $i); ?>
                <p>Fecha: <?= $fila['fecha'] ?> | Hora: <?= $fila['hora'] ?></p><?php
                }
            }
        }?>
    </body>
</html>
