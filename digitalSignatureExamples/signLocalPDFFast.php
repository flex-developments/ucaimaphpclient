<?php
/*
 * ucaimaPHPClient
 *
 * Copyright (C) 2010
 * Ing. Felix D. Lopez M. - flex.developments en gmail
 * 
 * Desarrollo apoyado por la Superintendencia de Servicios de Certificación 
 * Electrónica (SUSCERTE) durante 2010-2014 por:
 * Ing. Felix D. Lopez M. - flex.developments en gmail | flopez en suscerte gob ve
 * Ing. Yessica De Ascencao - yessicadeascencao en gmail | ydeascencao en suscerte gob ve
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

//OJO... Paso 0.- Cargar el string de configuración existente en BD
$appletPreviousConf = "";
//mysql_query("SET NAMES 'utf8'"); 
//$consulta = mysql_query("SELECT * FROM usuarios", $conexion);
//while ($confBD = mysql_fetch_array($consulta)) {
//    $appletPreviousConf = $confBD['conf'];
//}
//***********************************************************************************
?>

<html>
<head>
    <title>Prueba para firma de campos</title>
    
    <!--OJO... Paso 1.- Incluir funciones de JavaScript relacionadas al applet*******-->
        <script type="text/javascript" src="kawi/kawi.js"></script>
    <!--*****************************************************************************-->
    
    <script>
        function signLocalPDFF() {
            passLectura = null;
            passEscritura = null;
            
            if (document.form1.passLectura.disabled == false)
                passLectura = document.form1.passLectura.value;
            
            if (document.form1.passEscritura.disabled == false)
                passEscritura = document.form1.passEscritura.value;
            
            configuration = "<?php echo "$appletPreviousConf"; ?>";
            
            //Generar PDFs firmados
            result = kawiGenerateFastSignedPDFFiles(
                configuration, 
                "idPDF", 
                document.form1.origen.value,
                document.form1.destino.value, 
                passLectura, 
                passEscritura, 
                document.form1.razon.value, 
                document.form1.location.value,
                document.form1.contact.value, 
                document.form1.bloquearModificaciones.value, 
                document.form1.visible.value,
                document.form1.pagina.value,
                document.form1.imagen.value,
                1, 
                1, 
                200, 
                200, 
                0
            );
            
            alert("Resultado de firma = " + result);
        }
    </script>
    <center>
        <!--OJO... Paso 2.- Incluir etiqueta HTML del applet*************************-->
        <applet
            id       = 'kawi'
            code     = 'flex.kawi.applet.AppletKawi'
            archive  = 'kawi/kawi-v010.jar'
            name     = 'kawi'
            width    = '370'
            height   = '10'
            align    = 'middle'
            type     = 'application/x-java-applet'
            mayscript
        >
            <param name='centerimage' value='true'>
            <param name='boxborder' value='false'>
        </applet>
        <!--*************************************************************************-->
    </center>
</head>

<body>
    <center>
        <a href="javascript:history.back()">Volver</a><br>
        
        <form name="form1">
            <table border="1">
                <tr align="center">
                    <td class="tdizq" colspan="2">Firmar archivo PDF</td>
                </tr>
                <tr align="center">
                    <td>Origen: </td><td><input class="inputText" type="text" name="origen" 
                               value="/home/flopez/resources/prueba.pdf"></td>
                </tr>
                <tr align="center">
                    <td>Destino: </td><td><input class="inputText" type="text" name="destino" 
                               value="/home/flopez/resources/prueba-Firmado.pdf"></td>
                </tr>
                <tr align="center">
                    <td>PassLectura: </td><td><input class="inputText" type="text" name="passLectura" 
                               value="" disabled></td>
                </tr>
                <tr align="center">
                    <td>PassEscritura: </td><td><input class="inputText" type="text" name="passEscritura" 
                               value="" disabled></td>
                </tr>
                <tr align="center">
                    <td>Razon: </td><td><input class="inputText" type="text" name="razon" 
                               value="razon"></td>
                </tr>
                <tr align="center">
                    <td>Location: </td><td><input class="inputText" type="text" name="location" 
                               value="location"></td>
                </tr>
                <tr align="center">
                    <td>Contact: </td><td><input class="inputText" type="text" name="contact" 
                               value="contact"></td>
                </tr>
                <tr align="center">
                    <td>Algorithm: </td><td><input class="inputText" type="text" name="algorithm" 
                               value="SHA1withRSA"></td>
                </tr>
                <tr align="center">
                    <td>Bloquear Modificaciones: </td><td><input class="inputText" type="text" name="bloquearModificaciones" 
                               value="true"></td>
                </tr>
                <tr align="center">
                    <td>Visible: </td><td><input class="inputText" type="text" name="visible" 
                               value="true"></td>
                </tr>
                <tr align="center">
                    <td>Pagina: </td><td><input class="inputText" type="text" name="pagina" 
                               value="1"></td>
                </tr>
                <tr align="center">
                    <td>Imagen: </td><td><input class="inputText" type="text" name="imagen" 
                               value="/home/flopez/resources/fondo_firma.png"></td>
                </tr>
                <tr align="center">
                    <td colspan="2">
                        <!--OJO... Paso 3.- Llamar la función de firma--> 
                        <input type="button" 
                               value="Firmar PDF" 
                               class="tamLetra" 
                               onClick="return signLocalPDFF();"
                        >
                        <!--*********************************************************-->
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
</html>