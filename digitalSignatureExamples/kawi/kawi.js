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
//////////////////////////////// General ///////////////////////////////////
//Obtener objeto del applet
function getKawi() {
    return document.getElementById('kawi');
}

//Configurar applet
function kawiSetConfiguration(configuration) {
    result = getKawi().setConfiguration(configuration);
    if (result !== "TRUE") throw new Error(result);
    return true;
}
////////////////////////////////////////////////////////////////////////////

////////////////////////////////// PDF /////////////////////////////////////
//Agregar PDF a la cola del applet
function kawiAddPDF(
    key, 
    pdfInPath, 
    pdfOutPath,
    readPass, 
    writePass, 
    reason, 
    location, 
    contact, 
    noModify, 
    visible, 
    page,
    imgPath, 
    imgP1X, 
    imgP1Y, 
    imgP2X, 
    imgP2Y, 
    imgRotation
) {
    result = getKawi().addPDF(
        key, 
        pdfInPath, 
        pdfOutPath,
        readPass, 
        writePass, 
        reason, 
        location, 
        contact, 
        noModify, 
        visible, 
        page,
        imgPath, 
        imgP1X, 
        imgP1Y, 
        imgP2X, 
        imgP2Y, 
        imgRotation
    );
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Remover PDF de la cola del applet
function kawiRemovePDF(key) {
    result = getKawi().removePDF(key);
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Limpiar cola del applet
function kawiClearPDF() {
    result = getKawi().clearPDF();
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Generar PDFs con las firmas electronicas
function kawiGenerateSignedPDFFiles() {
    result = getKawi().generateSignedPDFFiles();
    
    //Evaluar resultado obtenido del applet
    if(result === "PKI_KEYS_CANCEL") {
        ////Ha presionado boton Cancelar durante la carga de las llaves
        return false;
        
    } if( (result.search("!...") > -1 ) || (!result) ) {
        
        //Ha ocurrido un error
        kawiClearPDF();
        throw new Error("ERROR");
    }
    return true;
}

//Configurar applet, agregar data y generar paquete con las firmas electronicas
function kawiGenerateFastSignedPDFFiles(
    configuration,
    key, 
    pdfInPath, 
    pdfOutPath,
    readPass, 
    writePass, 
    reason, 
    location, 
    contact, 
    noModify, 
    visible, 
    page,
    imgPath, 
    imgP1X, 
    imgP1Y, 
    imgP2X, 
    imgP2Y, 
    imgRotation
) {
    kawiSetConfiguration(configuration);
    
    kawiAddPDF(
        key, 
        pdfInPath, 
        pdfOutPath,
        readPass, 
        writePass, 
        reason, 
        location, 
        contact, 
        noModify, 
        visible, 
        page,
        imgPath, 
        imgP1X, 
        imgP1Y, 
        imgP2X, 
        imgP2Y, 
        imgRotation
    );
    
    return kawiGenerateSignedPDFFiles();
}

//Generar pdf firmado
//Deprecate
function signLocalPDF(
    pdfInPath,
    pdfOutPath,
    readPass,
    writePass,
    reason,
    location,
    contact,
    signAlg,
    noModify,
    visible,
    page,
    image,
    imgP1X,
    imgP1Y,
    imgP2X,
    imgP2Y,
    imgRotation
) {
    
    result = getKawi().signLocalPDF(
        pdfInPath,
        pdfOutPath,
        readPass, 
        writePass, 
        reason, 
        location, 
        contact, 
        signAlg, 
        noModify, 
        visible, 
        page,
        image,
        imgP1X,
        imgP1Y,
        imgP2X,
        imgP2Y,
        imgRotation
    );
    
    if (result !== "TRUE") throw new Error(result);
    return result;
}
////////////////////////////////////////////////////////////////////////////

////////////////////////////////// DATA ////////////////////////////////////
//Agregar data a la cola del applet
//Tratamiento puede ser: cadena_texto, archivo_local, archivo_remoto, pdf_remoto
//Segun lo disponible en flex.kawi.componente.cola.ColaKawi.TRATAMIENTO_*
//(0- String, 1- Local File, 2- Remote File, 3- Remote PDF File)
function kawiAddData(key, data, treatment) {
    result = getKawi().addData(key, data, treatment);
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Remover data de la cola del applet
function kawiRemoveData(key) {
    result = getKawi().removeData(key);
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Limpiar cola del applet
function kawiClearData() {
    result = getKawi().clearData();
    if (result !== "TRUE") throw new Error(result);
    return true;
}

//Generar paquete con las firmas electronicas
function kawiCreateKawiPackage() {
    kawiPackage = getKawi().generateKawiPack();
    
    //Evaluar resultado obtenido del applet
    if(kawiPackage === "PKI_KEYS_CANCEL") {
        ////Ha presionado boton Cancelar durante la carga de las llaves
        return false;
        
    } if( (kawiPackage.search("Error!...") > -1 ) || (!kawiPackage) ) {
        
        //Ha ocurrido un error
        kawiClearData();
        throw new Error("ERROR");
        
    } else { //Se ha generado el paquete de forma satisfactoria
        return kawiPackage;
    }
}

//Configurar applet, agregar data y generar paquete con las firmas electronicas
function kawiCreateKawiFastPackage(configuration, key, data, treatment) {
    kawiSetConfiguration(configuration);
    kawiAddData(key, data, treatment);
    return kawiCreateKawiPackage();
}
////////////////////////////////////////////////////////////////////////////

////////////////////////////// Adicionales//////////////////////////////////
function getUserHomeDirectory(){
    return getKawi().getUserHomeDirectory();
} 

function getTempDirectory(){
    return getKawi().getTempDirectory();
}
