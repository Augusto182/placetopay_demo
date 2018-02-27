<?php
/**
 * @file
 * Inicialización del cliente webservice y funciones relacionadas.
 */

// Inicialización de cliente webservice.
$options = array(
    'trace' => true,
);

$s = new SoapClient($WSDL, $options);
$s -> __setLocation('https://test.placetopay.com/soap/pse/');

/**
 * Define función getBankList
 *
 * Consume servicio getBankList.
 *
 * @return object
 *   Objeto de lista de bancos. 
 */
function getBankList() {
  // Objeto SoapClient
  global $s;
  // Objeto $auth
  global $auth;

  $param = array(
    'auth' => $auth
  );

  $banks = $s->getBankList($param);

  return $banks;
}

/**
 * Define función createTransaction
 *
 * Consume servicio createTransaction.
 *
 * @return object
 *   Objeto TransactionResponse. 
 */
function createTransaction($bank,$interface) {
  // Objeto SoapClient
  global $s;
  // Objeto $auth
  global $auth;	
  // Objeto $auth
  global $person;	
  // url response
  global $returnURL;
  // referencia
  // @todo: Mejorar la generación de un código de referencia aleatorio, debe ser único
  $referencia  = time();
  // descripción
  $descripcion = 'Prueba';

  $PSETransactionRequest = array(
  	'bankCode'        => $bank,
  	'bankInterface'   => $interface,
  	'returnURL'       => $returnURL.'?q='.$referencia,
  	'reference'  	  => $referencia,
  	'description'     => $descripcion,
  	'language'		  => 'ES',
  	'currency'		  => 'COP',
  	'totalAmount'     => '1190',
  	'taxAmount'       => '190',
  	'devolutionBase'  => '1000',
  	'tipAmount'       => '0',
  	'payer'  	      => $person,
  	'buyer'  		  => $person,
  	'shipping'  	  => $person,
  	'ipAddress'  	  => $_SERVER['REMOTE_ADDR'],
  	'userAgent'  	  => $_SERVER['HTTP_USER_AGENT'],
  	'additionalData'  => array(),
  );

  $param = array(
    'auth'        => $auth,
    'transaction' => $PSETransactionRequest,
  );

  $PSETransactionResponse = $s->createTransaction($param);

  $TransactionResponse    = $PSETransactionResponse->createTransactionResult;

  transaction_save($PSETransactionRequest,$PSETransactionResponse->createTransactionResult);

  return $TransactionResponse;
}

/**
 * Define función getTransactionInformation
 *
 * Consume servicio getTransactionInformation.
 *
 * @param int $transactionID
 *   identificador de la transacción. 
 *
 * @return object
 *   Objeto TransactionInformation. 
 */
function getTransactionInformation($transactionID) {
  // Objeto SoapClient
  global $s;
  // Objeto $auth
  global $auth;

  $param = array(
    'auth'          => $auth,
    'transactionID' => $transactionID
  );

  $TransactionInformation = $s->getTransactionInformation($param);

  return $TransactionInformation;
}

?>