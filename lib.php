<?php
/**
 * @file
 * Libreria de funciones generales.
 */

/**
 * Define función getBanks
 *
 * @return array
 *   Lista en la forma Codigo => Nombre
 */
function getBanks() {

  $now = time();
  // Cantidad de segundos en un día.
  $day = 86400;
  // Límite inferior
  $lim = $now - $day;
  // Bancos
  $banks = array();
  // Bandera
  $update = false;

  $link = conectar_mysql();

  // Inicializar o actualizar cache

  $sql  = "select value as last_banks_cache from variables where name = 'last_banks_cache'; ";

  $res = mysqli_query($link, $sql);

  if($res) {

  	if(mysqli_num_rows($res) === 0)  {
  	  // inicializa variable con timestamp actual
  	  $sql = "insert into variables values('last_banks_cache',UNIX_TIMESTAMP())";
	  mysqli_query($link,$sql);
      $update = true;
    }
    else  {
      $row = mysqli_fetch_assoc($res);
      if($row['last_banks_cache'] < $lim) {
        // actualiza variable con timestamp actual
  	    $sql = "update variables set value = UNIX_TIMESTAMP() where name = 'last_banks_cache'";
        $update = true;
      }
    }
  }

  // Actualizar cache
  if($update == true) {
  	// Limpiar actual contenido de cache
	$sql   = "truncate table cache_banks";
	mysqli_query($link,$sql);
	// Insertar valores actuales
    $banklist = getBankList();
    foreach($banklist->getBankListResult->item as $item) {
  	  $sql = "insert into cache_banks values(".$item->bankCode.",'".$item->bankName."')";
	  $x = mysqli_query($link,$sql);
    }
  }

  // Obtener bancos
    
  $sql = "select * from cache_banks";
  $res = mysqli_query($link, $sql);

  while ($row = mysqli_fetch_row($res)) {
    $banks[$row[0]] = $row[1];	
  }

  cerrar_mysql($link);

  return $banks;
}

/**
 * Define función renderSeleccionarBanco
 *
 * @return string
 *   HTML del campo de selección de bancos.
 */
function renderSeleccionarBanco() {

  $options = "";
  $banks   = getBanks();

  foreach($banks as $code=>$bank) {
  	$options .= "<option value='".$code."'>".$bank."</option>";
  }
  $select = "<select id='bank' name='bank'>".$options."</select>";

  return $select;
}

/**
 * Define función form_render
 *
 * @return string
 *   HTML del formulario completo.
 */
function form_render() {
  $html = "	
    <form name='placetopay' action='index.php' method='POST'>
      <select name='tipo'>
	    <option value='0'>Persona natural</option>
	    <option value='1'>Persona jurídica</option>
	  </select>
	"  
    .renderSeleccionarBanco()
    ." <input name='step1' value='Seguir' type='submit'>
    </form>";
   return $html; 
}

/**
 * Define función form_submit
 *
 * Procesa el formulario enviado. Crea la transacción.
 */
function form_submit() {

  if(isset($_POST['step1'])) {

  	$TransactionResponse = createTransaction($_POST['bank'],$_POST['tipo']);

  	if($TransactionResponse->returnCode == "SUCCESS") {
  	  redireccionar_set($TransactionResponse->bankURL);	
  	}
  	else {
  	  global $html;
  	  $html .= "<p>No se pudo establecer conexión con pasarela de pagos. </p>";
  	}
  }
}

/**
 * Define función transaction_save
 *
 * Guarda datos de transacción en base de datos.
 *
 * @param array $req
 *   Arreglo PSETransactionRequest 
 *
 * @param object $res
 *   Objeto PSETransactionResponse
 */
function transaction_save($req,$res) {
  
  $link = conectar_mysql();

  $sql  = "insert into transactions (
             transactionID,
             sessionID,
             returnCode,
             trazabilityCode,
             transactionCycle,
             bankCurrency,
             bankFactor,
             bankURL,
             responseCode,
             responseReasonCode,
             responseReasonText,
             bankCode,
             bankInterface,
             reference,
             description,
             totalAmount
             ) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = mysqli_prepare ($link, $sql);
  mysqli_stmt_bind_param(
  	$stmt, 
  	"isssisdsissssssd", 
  	$res->transactionID,
  	$res->sessionID,
  	$res->returnCode,
  	$res->trazabilityCode,
  	$res->transactionCycle,
  	$res->bankCurrency,
  	$res->bankFactor,
  	$res->bankURL,
  	$res->responseCode,
  	$res->responseReasonCode,
  	$res->responseReasonText,
  	$req['bankCode'],
  	$req['bankInterface'],
  	$req['reference'],
  	$req['description'],
  	$req['totalAmount']
  );
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  cerrar_mysql($link);
}

/**
 * Define función redireccionar_set
 *
 * Configura si la página actual debe redireccionarse.
 *
 * @param string $url
 *   URL a la que direccionar la página. 
 */
function redireccionar_set($url) {
  global $redireccionar;
  $redireccionar = $url;
}

/**
 * Define función redireccionar
 *
 * Configura si la página actual debe redireccionarse.
 *
 * @return string 
 *   HTML necesario para provocar la redirección de la página. 
 */
function redireccionar() {
  global $redireccionar;
  if($redireccionar) {
  	return "<meta http-equiv=\"refresh\" content=\"0;URL='$redireccionar'\" />";   
  }
  return "";
}

/**
 * Define función response
 *
 * Genera contenido de la página de respuesta.
 * Consume servicio getTransactionInformation
 *
 * @param string $ref
 *   Referencia de la transacción. 
 *
 * @return string 
 *   HTML contenido de la página de respuesta. 
 */
function response($ref) {

  // Obtener transactionID
  $link = conectar_mysql();
  $transactionID = false;
  $sql  = "select transactionID from transactions where reference = ?;";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt,"s",$ref);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $transactionID);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  cerrar_mysql($link);

  // Obtener respuesta de la transacción
  $TransactionInformation = getTransactionInformation($transactionID);

  $html = '<h2> El estado de la transacción es: '.$TransactionInformation->getTransactionInformationResult->transactionState.'</h2>';

  $html .= 'Detalles: <pre>'.print_r($TransactionInformation,true).'</pre>';

  // Actualizar transación
  transaction_update($TransactionInformation->getTransactionInformationResult);

  return $html;
}

/**
 * Define función transaction_update
 *
 * Actualiza datos de transacción en base de datos.
 *
 * @param object $res
 *   Objeto TransactionInformation. 
 */
function transaction_update($res) {

  $link = conectar_mysql();

  $sql  = "update transactions set
             requestDate = ?,
             bankProcessDate = ?,
             trazabilityCode = ?,
             transactionCycle = ?,
             transactionState = ?,
             responseCode = ?,
             responseReasonCode = ?,
             responseReasonText =  ?
             where transactionID = ?";
  $stmt = mysqli_prepare ($link, $sql);
  mysqli_stmt_bind_param(
  	$stmt, 
  	"sssisissi", 
  	$res->requestDate,
  	$res->bankProcessDate,
  	$res->trazabilityCode,
  	$res->transactionCycle,
  	$res->transactionState,
  	$res->responseCode,
  	$res->responseReasonCode,
  	$res->responseReasonText,
  	$res->transactionID
  );
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  cerrar_mysql($link);
}

/**
 * Define función logpage
 *
 * Genera contenido de la página de historial de transacciones.
 *
 * @return string
 *   HTML de la lista de transacciones realizadas. 
 */
function logpage() {

  $link = conectar_mysql();	

  $sql = "select * from transactions order by idt DESC";

  $res = mysqli_query($link, $sql);

  $html = "
    <h2>HISTORIAL DE TRANSACCIONES</h2>
    <h3>Recarga el navegador para actualizar los estados.</h3>

    <table border><tr>
      <th>requestDate</th>
      <th>returnCode</th>
      <th>transactionState</th>
      <th>responseCode</th>
      <th>responseReasonCode</th>
      <th>responseReasonText</th>
      <th>description</th>
      <th>totalAmount</th>
    </tr>
  ";

  while ($row = mysqli_fetch_object($res)) {
    $html .= "<tr>";
    $html .= "<td>".$row->requestDate."</td>";
    $html .= "<td>".$row->returnCode."</td>";
    $html .= "<td>".$row->transactionState."</td>";
    $html .= "<td>".$row->responseCode."</td>";
    $html .= "<td>".$row->responseReasonCode."</td>";
    $html .= "<td>".$row->responseReasonText."</td>";
    $html .= "<td>".$row->description."</td>";
    $html .= "<td>".$row->totalAmount."</td>";
    $html .= "</tr>";	
  }

  $html .= "
    </table>
  ";

  cerrar_mysql($link);
  return $html;
}

/**
 * Define función actualizar_estado_transacciones
 *
 * Actualiza estado de transacciones que se encuentren en estado PENDING.
 */
function   actualizar_estado_transacciones() {

  $link = conectar_mysql();	

  $sql = "select transactionID from transactions where transactionState	 = 'PENDING';";
  $res = mysqli_query($link, $sql);
  print mysqli_error($link);

  while ($row = mysqli_fetch_object($res)) {
  	$TransactionInformation = getTransactionInformation($row->transactionID);
    transaction_update($TransactionInformation->getTransactionInformationResult);	
  }

  cerrar_mysql($link);
}



?>