<?php
/*
 * ucaimaPHPClient
 *
 * Copyright (C) 2009
 * Ing. Felix D. Lopez M. - flex.developments en gmail
 * 
 * Desarrollo proseguido bajo relación laboral con la
 * Superintendencia de Servicios de Certificación Electrónica (SUSCERTE) por:
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

use Exception;
include_once('KawiPackSecction.class.php');
include_once('SignatureNode.class.php');

//Clase que permite ...
//Creada por Ing. Félix López - flex.developments en gmail
class KawiPackSignatures extends KawiPackSecction {
    //Para la generación y carga del paquete en XML
    private static $XML_SIGNATURES_SECCTION = "Signatures";
    private static $XML_SIGNATURE_CHILDS_ID = "Signature";
    private static $XML_ELEMENT_DATA = "signatureID";
    private static $XML_ELEMENT_SIGNATURE = "signatureData";
    private static $XML_ELEMENT_SIGNATURE_DATE = "signatureDate";
    
    private $signatures = array();
    
    public function add($id, $signature, $date="") {
        if (is_null($id)) {
            throw new Exception("Error al intentar agregar un id = null", 1001);
        }
        if (empty($id)) {
            throw new Exception("Error al intentar agregar un id = null vacio", 1001);
        }

        if (is_null($signature)) {
            throw new Exception("Error al intentar agregar una firma = null", 1001);
        }

        if (empty($signature)) {
            throw new Exception("Error al intentar agregar una firma = null vacio", 1001);
        }

        $signatureNode = new SignatureNode( ((string)$signature), ((string)$date) );
        $value = array( ((string)$id) => ($signatureNode) );
        $this->signatures = array_merge($this->signatures, $value);
    }
    
    public function getIds() {
        return array_keys($this->signatures);
    }
    
    public function getSignatureNodes() {
        return array_values($this->signatures);
    }
    
    public function getSignature($id) {
        $result = $this->signatures[$id]->getValue();
        if (is_null($result)) {
            throw new Exception("Errol al intentar buscar un id inexistente", 1001);
        }
        return $result;
    }
    
    public function getFecha($id) {
        $result = $this->signatures[$id]->getDate();
        if (is_null($result)) {
            throw new Exception("Errol al intentar buscar un id inexistente", 1001);
        }
        return $result;
    }
    
    //------------------------------- Abstractos -------------------------------
    public function clear() {
        unset($this->signatures);
        $this->signatures = array();
    }
    
    public function size() {
        return sizeof($this->signatures);
    }
    
    //---------------------------- Cargar desde XML ----------------------------
    public function loadFromXML($xml) {
        $aux = simplexml_load_string($xml); //Verifico integridad del xml
        $this->clear();
        foreach ($aux->xpath("//Signature") as $cab) { //OJO... $XML_FIRMA_CHILDS_ID
            $this->add($cab->idSignature, $cab->signatureData, $cab->signatureDate);
        }
    }
    
    public function toXML() {
        //Este método no ha sido probado
        $xml = "\t<" + self::$XML_SIGNATURES_SECCTION + ">\n";
        
        $keys = $this->getIds();
        foreach($keys as $key) {
//            $current = "\t\t<" + self::$XML_SIGNATURE_CHILDS_ID + ">\n" +
//                             "\t\t\t<" + self::$XML_ELEMENT_DATA + ">default</" + self::$XML_ELEMENT_DATA + ">\n" +
//                             "\t\t\t<" + self::$XML_ELEMENT_SIGNATURE + ">default</"+ self::$XML_ELEMENT_SIGNATURE + ">\n" +
//                             "\t\t\t<" + self::$XML_ELEMENT_SIGNATURE_DATE + ">default</"+ self::$XML_ELEMENT_SIGNATURE_DATE + ">\n" +
//                             "\t\t</" + self::$XML_SIGNATURE_CHILDS_ID + ">\n";
//            try {
                $current = "\t\t<" + self::$XML_SIGNATURE_CHILDS_ID + ">\n" +
                          "\t\t\t<" + self::$XML_ELEMENT_DATA + ">" + $key + "</" + self::$XML_ELEMENT_DATA + ">\n" +
                          "\t\t\t<" + self::$XML_ELEMENT_SIGNATURE + ">" + $this->getSignature($key) + "</"+ self::$XML_ELEMENT_SIGNATURE + ">\n" +
                          "\t\t\t<" + self::$XML_ELEMENT_SIGNATURE_DATE + ">" + $this->getFecha($key) + "</"+ self::$XML_ELEMENT_SIGNATURE_DATE + ">\n" +
                          "\t\t</" + self::$XML_SIGNATURE_CHILDS_ID + ">\n";
//            } catch (Exception $ex) {
//                ex.printStackTrace();
//            }
            $xml =  $xml + $current;
        }
        return $xml + "\t</" + self::$XML_SIGNATURES_SECCTION + ">\n";
    }
}
