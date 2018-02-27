<?php
/**
 * @file
 * Archivo de arranque de la aplicación.
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

require_once('config.php');
require_once('database.php');
require_once('lib.php');
require_once('webservice.php');

if(isset($_GET['q'])) {
  // Página de respuesta	
  $html = response($_GET['q']);
}
elseif(isset($_GET['log'])) {
  // Página de historia de transacciones	
  actualizar_estado_transacciones();	
  $html = logpage();	
}
else {
  // Página del formulario	
  form_submit();	
  $html = form_render();
}

// Plantilla HTML
require_once('page.php');

?>