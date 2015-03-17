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
include_once('KawiPackHeaders.class.php');
include_once('KawiPackSignatures.class.php');

//Clase que permite ...
//Creada por Ing. Félix López - flex.developments@gmail.com
class KawiPack{
    protected $xml;
    private static $XML_ROOT_KAWI_PACK = "KawiPack";
    private $headers;
    private $signatures;
    
    //Constructoras-------------------------------------------------------------
    public function __construct ($package) {
        $this->loadKawiPackFromXML($package);
    }
    
    //Operaciones Cabecera------------------------------------------------------
    public function getHeaders() {    
        return $this->headers;
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
    }
    
    public function getHeader($id){
        return $this->getHeaders()->getHeaders($id);
    }
        
    //Operaciones Firmas--------------------------------------------------------
    public function getSignatures() {
        return $this->signatures;
    }

    public function setSignatures($signatures) {
        $this->signatures = $signatures;
    }
    
    public function getSignaturesIds() {
        return $this->getSignatures()->getIds();
    }
    
    public function getSignature($signatureID) {
        return $this->getSignatures()->getSignature($signatureID);
    }
    
    public function getSignDate($signatureID) {
        return $this->getSignatures()->getDate($signatureID);
    }
    
    //Otras---------------------------------------------------------------------
    public function clear() {
        if (!is_null($this->headers)) {
            $this->headers->clear();
        }
        if (!is_null($this->signatures)) {
            $this->signatures->clear();
        }
    }
    
    public function loadKawiPackFromXML($package) {
        $this->xml = $package;
        $aux = simplexml_load_string($this->xml); //Verificar integridad del xml
        if (!is_object($aux)) {
            print("Error en la lectura del XML");
            throw new Exception("Error en la lectura del XML", 1001);
        }
        
        $this->clear();
        $this->headers = new KawiPackHeaders();
        $this->signatures = new KawiPackSignatures();
        $this->headers->loadFromXML($this->xml);
        $this->signatures->loadFromXML($this->xml);
    }
    
    public function toXML() {
        return $this->xml; //OJO...quitar
        /*
        $xml = "<?xml version=\"1.0\"?>\n" +
                     "<" + self::$XML_ROOT_KAWI_PACK + ">\n" +
                        $this->cabeceras->toXML() + 
                        $this->firmas->toXML() +
                     "</" + self::$XML_ROOT_KAWI_PACK + ">";
        return $xml;
        */
    }
}
