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

//Clase que permite ...
//Creada por Ing. Félix López - flex.developments@gmail.com
class SignatureNode {
    private $value;
    private $date;
    
    public function __construct($value, $date) {
        $this->value = $value;
        $this->date = $date;
    }

    public function getValue() {
        return $this->value;
    }

    public function getDate() {
        return $this->date;
    }
}
