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
mysql_query("SET NAMES 'utf8'"); 
$req = mysql_query("SELECT * FROM usuarios", $con);

//OJO... Paso 0.- Cargar el string de configuración existente en BD
$appletPreviousConf = "";
while ($dbConf = mysql_fetch_array($req)) {
    $appletPreviousConf = $dbConf['conf'];
}
//***********************************************************************************
?>

<html>
<head>
    <title>Prueba para firma de campos</title>
    
    <!--OJO... Paso 1.- Incluir funciones de JavaScript relacionadas al applet*******-->
        <script type="text/javascript" src="kawi/kawi.js"></script>
    <!--*****************************************************************************-->
    
    <script>
        function signAndSend() {
            //Construir string que se firmará
            data = document.form1.nombre.value+document.form1.apellido.value;
            
            configuration = "<?php echo "$appletPreviousConf"; ?>";
            
            //Generar paquete con las firmas electrónicas
            try {
                
                kawiPackage = kawiCreateKawiFastPackage(
                    configuration,
                    "signID", 
                    data, 
                    0
                );
                if(!kawiPackage) return false;
                
                document.form1.digitalSignature.value = kawiPackage;
                return true;
                
            } catch (ex) {
                alert("No se generó la firma electrónica");
                return false;
            }
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
        
        <form method="post" action="signFieldsProcess.php" name="form1">
            <table border="1">
                <tr align="center">
                    <td class="tdizq">Firma de información en BD</td>
                </tr>
                <tr align="center">
                    <td><input class="inputText" type="text" name="nombre" size="20" alt="Nombre" value="Nombre" title="Introduzca su Nombre"></td>
                </tr>
                <tr align="center">
                    <td><input class="inputText" type="text" name="apellido" size="20" alt="Apellido" value="Apellido" title="Introduzca su Apellido"></td>
                </tr>
                <tr align="center">
                    <td>
                        <!--OJO... Paso 3.- Incluir campo HTML que recibirá y transportará el resultado del paquete-->
                        <input class="inputText" type="hidden" name="digitalSignature">
                        <!--*********************************************************-->
                        
                        <!--OJO... Paso 4.- Llamar la función de firma--> 
                        <input type="submit" 
                               value="Firmar y Guardar" 
                               class="tamLetra" 
                               onClick="return signAndSend();"
                        >
                        <!--*********************************************************-->
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
</html>