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
include_once('KawiPackSecction.class.php');

//Clase que permite ...
//Creada por Ing. Félix López - flex.developments@gmail.com
class KawiPackHeaders extends KawiPackSecction {
    private static $XML_HEADERS_SECCTION = "Headers";
    private static $XML_HEADER_CHILDS_ID = "Header";
    private static $XML_HEADER_ID = "idHeader";
    private static $XML_HEADER_VALUE = "Value";
    
    private $headers = array();
    
    public function add($id, $value) {
        if (is_null($id)) {
            throw new Exception("Error al intentar agregar un id = null", 1001);
        }
        if (empty($id)) {
            throw new Exception("Error al intentar agregar un id = null vacio", 1001);
        }

        $arr = array( ((string)$id) => ((string)$value) );
        $this->headers = array_merge($this->headers, $arr);
    }
    
    public function remove($id) {
        unset($this->headers[$id]);
    }
    
    public function getIds() {
        return array_keys($this->headers);
    }

    protected function getValues() {
        return array_values($this->headers);
    }
    
    public function getHeaders($id) {
        $result = $this->headers[$id];
        if (empty($result)) {
            throw new Exception("Errol al intentar buscar un id inexistente", 1001);
        }
        return $result;
    }
    
    //------------------------------- Abstractos -------------------------------
    public function clear() {
        unset($this->headers);
        $this->headers = array();
    }
    
    public function size() {
        return sizeof($this->headers);
    }
    
    //---------------------------- Cargar desde XML ----------------------------
    public function loadFromXML($xml) {
        $aux = simplexml_load_string($xml); //Verifico integridad del xml
        $this->clear();
        foreach ($aux->xpath("//Header") as $cab) { //OJO... $XML_CABECERA_CHILDS_ID
            $this->add($cab->idHeader, $cab->Value);
        }
    }
    
    public function toXML() {
        //Este método no ha sido probado
        $xml = "\t<" + self::$XML_HEADERS_SECCTION + ">\n";
        
        $keys = $this->getIds();
        foreach($keys as $key) {
            $xml =  $xml + 
                    "\t\t<" + self::$XML_HEADER_CHILDS_ID + ">\n" +
                    "\t\t\t<" + self::$XML_HEADER_ID + ">" + $key + "</" + self::$XML_HEADER_ID + ">\n" +
                    "\t\t\t<" + self::$XML_HEADER_VALUE + ">" + $this->getHeaders($key) + "</"+ self::$XML_HEADER_VALUE + ">\n" +
                    "\t\t</" + self::$XML_HEADER_CHILDS_ID + ">\n";
        }
        return xml + "\t</" + self::$XML_HEADERS_SECCTION + ">\n";
    }
}
