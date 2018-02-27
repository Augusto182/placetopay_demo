<?php
/**
 * @file
 * Variables globales de configuración.
 */

// Base de datos
$mysql_config = array(
  'host' => "localhost",
  'user' => "plazapub_test",
  'pass' => "suyotest.182",
  'dbas' => "plazapub_suyotest"
);

// Webservice
$ident   = '6dd490faf9cb87a9862245da41170ff2';
$tranKey = '024h1IlD';
$WSDL    = 'https://test.placetopay.com/soap/pse/?wsdl';

$seed    = date('c');
$tranKey = sha1($seed.$tranKey);
$additio = array();

// Auth
$auth  = array(
  'login'      => $ident,
  'tranKey'    => $tranKey,
  'seed'       => $seed,
  'additional' => $additio,
);

// Persona
$person = array(
  'document'     => '80076492',
  'documentType' => 'CC',
  'firstName'    => 'Augusto',
  'lastName'     => 'Ramírez',
  'company'      => 'PlaceToPay',
  'emailAddress' => 'augusto182@gmail.com',
  'address'      => 'cr 49 # 61 - 11 Apto 400',
  'city'         => 'Medellín',
  'province'     => 'Antioquia',
  'country' 	 => 'CO',
  'phone'		 => '2869577',
  'mobile'		 => '3046829725',
);

// Punto de retorno de los clientes.
$returnURL = 'http://plazapublica.com.co/placetopay/placetopay_demo/index.php';

// Contenido html a mostrar en la plantilla
$html = '';

// Bandera que indica si debe generarse redirección
$redireccionar = false;

?>