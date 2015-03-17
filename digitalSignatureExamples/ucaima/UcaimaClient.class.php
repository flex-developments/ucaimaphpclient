<?php
/*
 * ucaimaPHPClient
 *
 * Copyright (C) 2009
 * Ing. Felix D. Lopez M. - flex.developments@gmail.com
 * 
 * Desarrollo proseguido bajo relación laboral con la
 * Superintendencia de Servicios de Certificación Electrónica (SUSCERTE) por:
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

use Exception;
include_once('KawiPackage/KawiPack.class.php');
include_once('RestClient.class.php');

//Clase Abstracta que permite obtener instancia de los diferentes clientes de WS
//Creada por Ing. Félix López - flex.developments@gmail.com
class UcaimaClient{
    protected $package;
    protected $ws_url;
    
    //Obtener Instancia---------------------------------------------------------
    public function __construct ($url_ws = "http://localhost:8080/ucaima-v010") {
        $this->ws_url = $url_ws."/webresources";
    }
    
    //Consumo del WS)-----------------------------------------------------------
    public function unpackKawiPackToXML($cypher = ""){
        $args = array('pack'=>$cypher);
        $wsClient = new RestClient($this->ws_url."/unpackkawipacktoxml", "POST", $args);
        $wsClient->execute();
        $this->kawiPackageClear();
        $package = $wsClient->getResponseBody();
        $this->package = new KawiPack($package);
    }
    
    public function verifyStringSignature(
            $string = "",
            $certificate = "",
            $encodedSign = "",
            $signDate = "",
            $signAlg = ""
    ) {
        $argumentos = array("string"=>$string,
                            "signDate"=>$signDate,
                            "encodedSign"=>$encodedSign,
                            "certificate"=>$certificate,
                            "signAlg"=>$signAlg
        );

        $clienteWS = new RestClient($this->ws_url."/verifyencodedsignofstring", "POST", $argumentos);
        $clienteWS->execute();
        return $clienteWS->getResponseBody();
    }
    
    public function signLocalPDF(
            $pdfInPath = "", 
            $pdfOutPath = "", 
            $readPass = "", 
            $writePass = "", 
            $reason = "", 
            $location = "", 
            $contact = "", 
            $signAlg = "",
            $noModify = "",
            $visible = "", 
            $page = "", 
            $image = "",
            $imgP1X = "",
            $imgP1Y = "",
            $imgP2X = "", 
            $imgP2Y = "", 
            $imgRotation = ""
    ) {
        $argumentos = array("pdfInPath"=>$pdfInPath,
                            "pdfOutPath"=>$pdfOutPath,
                            "readPass"=>$readPass,
                            "writePass"=>$writePass,
                            "reason"=>$reason,
                            "location"=>$location,
                            "contact"=>$contact,
                            "signAlg"=>$signAlg,
                            "noModify"=>$noModify,
                            "visible"=>$visible,
                            "page"=>$page,
                            "image"=>$image,
                            "imgP1X"=>$imgP1X,
                            "imgP1Y"=>$imgP1Y,
                            "imgP2X"=>$imgP2X,
                            "imgP2Y"=>$imgP2Y,
                            "imgRotation"=>$imgRotation
        );
        
        $clienteWS = new RestClient($this->ws_url."/signlocalpdf", "POST", $argumentos);
        $clienteWS->execute();
        return $clienteWS->getResponseBody();
    }
    
    //Implementadas (Operan sobre el paquete)-----------------------------------
    protected function kawiPackageClear() {
        unset($this->package);
    }
    
    public function getPackageSignDate(){
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getHeader("fecha");
    }
    
    public function getPackageCertificate(){
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getHeader("certificado");
    }
    
    public function getPackageConfiguration(){
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getHeader("configuracion");
    }
    
    public function getPackageSignAlg(){
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getHeader("algoritmoFirma");
    }
    
    //Firmas
    public function getSignature($signatureID) {
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getSignature($signatureID);
    }
    
    public function getSignDate($signatureID) {
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->getPackageSignDate($signatureID);
    }
    
    //XML
    public function getXMLPackage() {
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }
        return $this->package->toXML();
    }
    
    public function verifyPackageSignature($string, $signatureID) {
        if (is_null($this->package)) {
            throw new Exception("No se ha procesado un paquete", 1001);
        }

        $certificate = $this->getPackageCertificate();
        $encodedSignature = $this->getSignature($signatureID);
        $signDate = $this->getSignDate($signatureID);
        $signAlg = $this->getPackageSignAlg();
        
        return $this->verifyStringSignature($string, $certificate, $encodedSignature, $signDate, $signAlg);
    }
}
?>
