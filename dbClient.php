<?php
//Archivo que permite establecer la conexion a la base de datos correspondiente
function conexion(){
    //$id_Conn = mysql_connect("server-bd.lab","admin","123456");
    $id_Conn = mysql_connect("127.0.0.1","root","123456");
    //$id_Conn = mysql_connect("127.0.0.1","root","");
    
    if($id_Conn == 0) {
            echo "Fallo la conexión a la base de datos!!<br>" ;
            $sqlerror = mysql_error($id_Conn);
            echo"$sqlerror";
    } else {
        //echo "La conexión a  la base de datos fue Satisfactoria!!";
        //echo "<br>";
        mysql_select_db('firma_db',$id_Conn); 
        return $id_Conn;
    }
}

$con= conexion();
?>