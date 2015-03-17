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
use InvalidArgumentException;

//Clase que permite el consumo de un WebService RestFull
//Fuente original http://es.scribd.com/doc/96489760/189/Ejemplo-de-uso-de-un-servicio-REST-CRUDL
//Modificada por Ing. Félix López - flex.developments@gmail.com
class RestClient{
    protected $url;
    protected $metodo;
    protected $requestBody;
    protected $requestLength;
    protected $acceptType;
    protected $responseBody;
    protected $responseInfo;
        
    public function __construct ($url, $metodo, $requestBody = null) {
        $this->url = $url;
        $this->metodo = $metodo;
        $this->requestBody = $requestBody;
        $this->requestLength = 0;
        $this->acceptType = 'application/json';
        $this->responseBody = null;
        $this->responseInfo = null;
        
        if ((!is_null($this->requestBody)) && (!empty($this->requestBody))) {
            $this->buildPostBody();
        }
    }
    
    public function buildPostBody ($data) {
        if( (is_null($data)) || (empty($data))) {
            $data = $this->requestBody;
        }
        
        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid data input for postBody. Array expected");
        }

        //$this->requestBody = http_build_query($data, "", "&"); //En algunos casos cambia el nombre de los parametros añadiendo el sufijo %5B0%5D
        $this->requestBody = $this->encode_array($data, "&");
    }
    
    private function encode_array($args, $paramSeparator) {
        if (!is_array($args)) {
            return false;
        }
        $c = 0;
        $out = '';
        foreach($args as $name => $value) {
            if ($c++ != 0) {
                $out .= $paramSeparator;
            }
            $out .= urlencode("$name").'=';
            if(is_array($value)) {
                $out .= urlencode(serialize($value));
            } else {
                $out .= urlencode("$value");
            }
        }
        return $out . "\n";
    }
    
    public function execute () {
        $ch = curl_init();
        try {
            switch (strtoupper($this->metodo)) {
                case 'GET':
                    $this->executeGet($ch);
                    break;
                case 'POST':
                    $this->executePost($ch);
                    break;
                case 'PUT':
                    $this->executePut($ch);
                    break;
                case 'DELETE':
                    $this->executeDelete($ch);
                    break;
                default:
                    throw new InvalidArgumentException('El metodo ('.$this->metodo.')es un metodo REST invalido.');
            }
            
        } catch (InvalidArgumentException $e) {
            curl_close($ch);
            throw $e;
        } catch (Exception $e) {
            curl_close($ch);
            throw $e;
        }
    }
    
    protected function executeGet ($ch) {
        $this->doExecute($ch);
    }
    
    protected function executePost ($ch) {
        if (!is_string($this->requestBody)) {
            $this->buildPostBody();
        }
        
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
        curl_setopt($ch, CURLOPT_POST, 1);
        $this->doExecute($ch);
    }
    
    protected function executePut ($ch) {
        if (!is_string($this->requestBody)) {
            $this->buildPostBody();
        }
        $this->requestLength = strlen($this->requestBody);
        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $this->requestBody);
        rewind($fh);
        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
        curl_setopt($ch, CURLOPT_PUT, true);
        $this->doExecute($ch);
        fclose($fh);
    }
    
    protected function executeDelete ($ch) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->doExecute($ch);
    }
    
    protected function doExecute (&$ch) {
        $this->setCurlOpts($ch);
        $this->responseBody = curl_exec($ch);
        $this->responseInfo = curl_getinfo($ch);
        curl_close($ch);
    }
    
    protected function setCurlOpts (&$ch) {
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: ' . $this->acceptType));
    }
    
    public function getResponseBody () {
        return $this->responseBody;
    }
    
    public function getResponseInfo () {
        return $this->responseInfo;
    }
    
    public function flush () {
        $this->requestBody = null;
        $this->requestLength = 0;
        $this->metodo = 'GET';
        $this->responseBody = null;
        $this->responseInfo = null;
    }
    
    public function getAcceptType () {
        return $this->acceptType;
    }
    
    public function setAcceptType ($acceptType) {
        $this->acceptType = $acceptType;
    }
    
    public function getUrl () {
        return $this->url;
    }
    
    public function setUrl ($url) {
        $this->url = $url;
    }
    
    public function getMetodo () {
        return $this->metodo;
    }
    
    public function setMetodo ($metodo) {
        $this->metodo = $metodo;
    } 
}
