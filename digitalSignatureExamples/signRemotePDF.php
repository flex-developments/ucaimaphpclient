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
?>
<html>
<head>
    <title>Pagina de Prueba</title>

    <!--OJO... Paso 1.- Incluir funciones de JavaScript relacionadas al applet*******-->
    <script type="text/javascript" src="kawi/kawi.js"></script>
    <!--*****************************************************************************-->

</head>

<body>
    <center>
        <p><img src="banner.jpg"></p><br>
        <a href="javascript:history.back()"> Volver Atrás</a> 
<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
include_once('dbClient.php');//incluimos la clase de conexion a la BD
include_once('ucaima/UcaimaClient.class.php');//incluimos la clase que nos permite operar los paquetes producidos por Kawi
mysql_query("SET NAMES 'utf8'");

//************************************************************************************************************
//Prueba para Firmar PDF por el servidor
//OJO... Esta operación no requiere la interación con ningún paquete de kawi
//Crear el cliente del WS
$ucaima = new UcaimaClient();

//Invoco el servicio para firmar electrónicamente el archivo
$ucaima->signLocalPDF(
        "prueba.pdf", 
        "prueba-Firmado.pdf", 
        null, null, "reasonPHP", "locationPHP", "contactPHP", 'SHA-256', "false", "0");
?>
            </center>
        </body>
</html>