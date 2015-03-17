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
<head>
    <title>Prueba para verificación en lote de firma de campos</title>
</head>
    
<body>
    <center>
        <a href="javascript:history.back()">Volver</a><br>
<?php

//***********************************************************************************
//Incluir la clase que nos permite operar contra el WS
    include_once('ucaima/UcaimaClient.class.php');
    
//Crear el cliente del WS
    $ucaima = new UcaimaClient();
    
//Consultamos la base de datos para extraer los datos que se verificaran
    $req=mysql_query("SELECT * FROM usuarios", $con);

while ($dbConf = mysql_fetch_array($req)) {
    //Extraer los datos para verificación
        //Concatenar la data que se firmo
            $nombre = $dbConf['nombre'];
            $apellido = $dbConf['apellido'];
        $data = $nombre.$apellido;
        
        $certificate = $dbConf['cert'];
        $signature = $dbConf['firma'];
        $signDate = $dbConf['fechaFirma'];
        $signAlg = $dbConf['algoritmoFirma'];
    
    //Ejecutar verificación de firma electrónica
        $verify = $ucaima->verifyStringSignature($data, $certificate, $signature, $signDate, $signAlg);
//**********************************************************************************
    echo'<table border="1">';
    echo"<tr>";
    echo"<td>Nombre</td>";
    echo"<td>Apellido</td>";
    echo"<td>Firma</td>";
    echo"<td>Fecha Firma</td>";
    echo"<td>Certificado</td>";
    echo"<td>Configuracion</td>";
    echo"<td>Algoritmo</td>";
    echo"<td>Resultado Verificacion</td>";
    echo"</tr>";
    echo"<tr><td>";
    echo $nombre;
    echo"</td><td>";
    echo $apellido;
    echo"</td><td>";
    echo "<pre>".$signature."</pre>";
    echo"</td><td>";
    echo "<pre>".$signDate."</pre>";
    echo"</td><td>";
    echo "<pre>".$certificate."</pre>";
    echo"</td><td>";
    echo "<pre>".$dbConf['conf']."</pre>";
    echo"</td><td>";
    echo "<pre>".$signAlg."</pre>";
    echo"</td><td>";
    echo "<pre>".$verify."</pre>";
    echo"</td></tr>";
    echo"</table>";
}
?>
            </center>
        </body>
</html>