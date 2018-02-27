<?php
/**
 * @file
 * Capa de abstracción para base de datos MySql.
 */

/**
 * Define función conectar_mysql
 *
 * Inicia conexión con base de datos mysql
 *
 * @return object
 *   Referencia a la conexión. 
 */
function conectar_mysql() {

  global $mysql_config;

  $link = mysqli_connect($mysql_config['host'],$mysql_config['user'], $mysql_config['pass'], $mysql_config['dbas']);

  if (!$link) {
    echo "<br/>Error: Unable to connect." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
  }

  return $link;
}

/**
 * Define función cerrar_mysql
 *
 * Cierra conexión con base de datos mysql
 *
 * @param object
 *   Referencia a la conexión. 
 */
function cerrar_mysql($link) {
  mysqli_close($link);
}

?>