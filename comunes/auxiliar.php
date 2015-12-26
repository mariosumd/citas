<?php

function conectar()
{
return pg_connect("host=localhost user=examen password=examen    
                   dbname=examen");
}

function comprobar_usuario_id($id)
{
    $res = pg_query_params("select * from usuarios where id_usuario = $1", array($id));
    
    if (pg_num_rows($res) == 0) {
        header("Location: ../index.php");
        return;
    }
}

function volver() { ?>
        <a href="../index.php"><input type="button" value="Volver" /></a><?php
}

function dar_cita($id_usuario, $fecha, $hora)
{
    $pqp = compact('fecha', 'hora', 'id_usuario');
    pg_query_params("insert into citas (fecha, hora, id_usuario)
                     values ($1, $2, $3)", $pqp);
}

function anular_cita($id_usuario)
{
    $pqp = compact('id_usuario');
    pg_query_params("delete from citas where id_usuario = $1", $pqp);
}

function comprobar_tiene_cita($id_usuario) 
{
    $res = pg_query_params("select * from citas where id_usuario = $1", array($id_usuario));
    
    if (pg_num_rows($res) > 0) {
        return true;
    }
    return false;
}