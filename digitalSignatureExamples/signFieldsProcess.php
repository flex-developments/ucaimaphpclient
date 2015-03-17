<?php
/*
 * ucaimaPHPClient
 *
 * Copyright (C) 2010
 * Ing. Felix D. Lopez M. - flex.developments@gmail.com
 * 
 * Desarrollo apoyado por la Superintendencia de Servicios de Certificación 
 * Electrónica (SUSCERTE) durante 2010-2014 por:
 * Ing. Felix D. Lopez M. - flex.developments@gmail.com | flopez@suscerte.gob.ve
 * Ing. Yessica De Ascencao - yessicadeascencao@gmail.com | ydeascencao@suscerte.gob.ve
 *
 * Este programa es software libre; Usted puede usarlo bajo los terminos de la
 * licencia de software GPL version 2.0 de la Free Software Foundation.
 *
 * Este programa se distribuye con la esperanza de que sea util, pero SIN
 * NINGUNA GARANTIA; tampoco las implicitas garantias de MERCANTILIDAD o
 * ADECUACION A UN PROPOSITO PARTICULAR.
 * Consulte la licencia GPL para mas detalles. Usted debe recibir una copia
 * de la GPL junto con este programa; si no, escriba a la Free Software
 * Foundation Inc. 51 Franklin Street,5 Piso, Boston, MA 02110-1301, USA.
 */

include_once('../dbClient.php');
header("Content-Type: text/html;charset=utf-8");
session_start();
mysql_query("SET NAMES 'utf8'");
?>
<html>
    <head><title>Página de Prueba</title></head>
    <body>
        <center>
            <a href="javascript:history.back()">Volver</a><br>
<?php

//***********************************************************************************
//Recibir data que se firmó
    $nombre1            =   mysql_real_escape_string($_POST['nombre']);
    $apellido1          =   mysql_real_escape_string($_POST['apellido']);
    
//Recibir paquete generado por kawi
    $digitalSignature   = $_POST['digitalSignature'];
    
//Incluir la clase que permite operar los paquetes producidos por Kawi
    include_once('ucaima/UcaimaClient.class.php');
    
//Crear el cliente del WS
    $ucaima = new UcaimaClient();
    
//Solicitar extracción del paquete
    $ucaima->unpackKawiPackToXML($digitalSignature);
    
//Obtener información del paquete
    $signAlg            =   $ucaima->getPackageSignAlg();
    $configuration      =   $ucaima->getPackageConfiguration();
    $certificate        =   $ucaima->getPackageCertificate();
    $encodeSignature    =   $ucaima->getSignature("signID");
    $signDate           =   $ucaima->getSignDate("signID");
    
//Verificar data y firma antes de guardar
    $verify = $ucaima->verifyPackageSignature($nombre1.$apellido1, "signID");
//Si resulta exitosa la verificacion, incluir datos en base de datos
//***********************************************************************************
    
    if($verify == "true") {
        $result = mysql_query("INSERT INTO usuarios values('','$nombre1','$apellido1','$encodeSignature', '$signDate','$certificate','$configuration','$signAlg')", $con);

        if($result){
            $men="Ingreso Exitoso";
            echo "<script>alert('$men')</script>";
        }else{
            $men="Falló el ingreso";
            echo "<script>alert('$men')</script>";
        }
    } else {
        print("<br>No se realizó la inserción ya que la verificación de la firma ha fallado");
    }

//Imprimir contenido de la base de datos
$req=mysql_query("SELECT * FROM usuarios", $con);
mysql_num_rows($req);

while ($dbConf = mysql_fetch_array($req)) {
    echo'<table border="1">';
    echo"<tr>";
        echo"<td>Nombre</td>";
        echo"<td>Apellido</td>";
        echo"<td>Firma</td>";
        echo"<td>Fecha Firma</td>";
        echo"<td>Certificado</td>";
        echo"<td>Configuracion</td>";
        echo"<td>Algoritmo</td>";
    echo"</tr>";
    echo"<tr><td>";
        echo $dbConf['nombre'];
        echo"</td><td>";
        echo $dbConf['apellido'];
        echo"</td><td>";
        echo "<pre>".$dbConf['firma']."</pre>";
        echo"</td><td>";
        echo "<pre>".$dbConf['fechaFirma']."</pre>";
        echo"</td><td>";
        echo "<pre>".$dbConf['cert']."</pre>";
        echo"</td><td>";
        echo "<pre>".$dbConf['conf']."</pre>";
        echo"</td><td>";
        echo "<pre>".$dbConf['algoritmoFirma']."</pre>";
    echo"</td></tr>";
    echo"</table>";
}
?>
            </center>
        </body>
</html>