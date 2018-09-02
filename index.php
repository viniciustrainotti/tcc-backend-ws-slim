<?php

use Slim\Http\Request;

require 'vendor/autoload.php';

$app = new Slim\App();

//$container = $app->getContainer();
//$container['upload_directory'] = 'C:/wamp/www/slimtest/uploads';

$app->get('/all',function() use ($app){

	require_once('dbconnect.php');
	
	$query = "SELECT * FROM dispositivos order by iddispositivo";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;
	}
	//Request $request, Response $response, $args
	//$response->json_encode($data);
	//return $response;
	
	echo json_encode($data);
	
});

$app->get('/',function(){

	echo "Hello World";
	
});

$app->get('/all/{pvid}',function($request) use ($app){

	//echo "Hello $pvid";

	require_once('dbconnect.php');
	
	$pvid = $request->getAttribute('pvid');
	
	$query = "SELECT * FROM dispositivos WHERE pvid =$pvid order by iddispositivo";
	$result = $mysqli->query($query);
	
	//verificar o valor alterado do conectado do device para vincular com o repond
	
	$teste = "0";
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;
		$teste = $row["pvid"];
	}
	
	/*$data = array_filter($data);
	
	if(!empty($data)){
		$response = 1;
	}else{
		$response = 0;
	}*/
	
	//echo $teste;	
	//echo json_encode($data);

	if($teste > 0){
		$response = "1";
	}else{
		$response = "0";
	}
	
	//echo $response;
	return $response;
	
});

//atualiza o device para conectado
$app->get('/all/at/{atualiza}',function($request) use ($app){

	require_once('dbconnect.php');
	
	$pvid = $request->getAttribute('atualiza');
	
	/*$query = "SELECT * FROM dispositivos WHERE pvid =$pvid order by iddispositivo";
	$result = $mysqli->query($query);*/
	
	$query1 = "UPDATE dispositivos SET conectado = '1' WHERE pvid =$pvid";
	$result1 = $mysqli->query($query1);
	
	$response = "1";

	return $response;

});

//status de um determinado dispositivo 
$app->get('/all/st/{status}',function($request) use ($app){

	//echo "Hello $pvid";

	require_once('dbconnect.php');
	
	$pvid = $request->getAttribute('status');
	
	$query = "SELECT * FROM dispositivos WHERE pvid =$pvid order by iddispositivo";
	$result = $mysqli->query($query);
	
	//echo $query;
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["conectado"];
	}
	
	//echo $teste;	
	//echo json_encode($data);

	if($teste == 1){
		$response = "1";
	}else{
		$response = "0";
	}
	
	//echo $response;
	return $response;
	
	//echo json_encode($data);
	
});

// serviços disponiveis
$app->get('/all/sv/{pvid}',function($request) use ($app){

	//echo "Hello $pvid";

	require_once('dbconnect.php');
	
	$pvid = $request->getAttribute('pvid');
	
	$query = "SELECT * FROM dispositivos WHERE pvid =$pvid order by iddispositivo";
	$result = $mysqli->query($query);
	
	//echo $query;
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["servicos"];
	}
	
	//echo $teste;	
	//echo json_encode($data);

	if($teste == 1){
		$response = "1";
	}else{
		$response = "0";
	}
	
	//echo $response;
	return $response;
	
	//echo json_encode($data);
	
});

//fim do download do serviços
$app->get('/all/svend/{atualiza}',function($request) use ($app){

	require_once('dbconnect.php');
	
	$pvid = $request->getAttribute('atualiza');
	
	$query1 = "UPDATE dispositivos SET servicos = '0' WHERE pvid =$pvid";
	$result1 = $mysqli->query($query1);
	
	$response = "1";

	return $response;

});

// 1 - down

$app->get('/download/{grupo}/{nome}', function($req, $res, $args) {


	$nome = $req->getAttribute('nome');
	
	$grupo = $req->getAttribute('grupo');
    
	//echo $nome;
	
	//echo $grupo;
	//C:\wamp\www\slimtest\uploads\PING
	
	$file = 'C:/wamp/www/slimtest/uploads/' . $grupo . '/' . $nome;
	$response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
return $response;
});


$app->post('/uploadfile', function(Request $request, Response $response) {
	
	//$dispositivo = $_POST['disp'];
	//$perfil = $_POST['perfil'];
	//$servico = $_POST['servico'];

	$uploaddir = 'C:/wamp/www/slimtest/retorno/'; 
	//. $dispositivo . '/' . $perfil . '/' . $servico . '/';

	echo $uploaddir;

	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	
	$arquivo = basename($_FILES['userfile']['name']);

	echo '<pre>';
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		//$sql = mysql_query("INSERT INTO scripts (nomescript, gruposcript) VALUES ('$arquivo', '$grupo')") or die(mysql_error());
		
	/*	$sql = mysql_query("SELECT * FROM scripts WHERE nomescript = '$arquivo' AND gruposcript = '$grupo'") or die(mysql_error());
		
		$row = mysql_num_rows($sql);
		
		if($row > 0){*/
			echo '<a>'.$arquivo.'</a><br />';
		
	/*	}else{
			$sql = mysql_query("INSERT INTO scripts (nomescript, gruposcript) VALUES ('$arquivo', '$grupo')") or die(mysql_error());
		}
		*/
		echo "Arquivo válido e enviado com sucesso.\n";
//	} else {
	//	echo "Possível ataque de upload de arquivo!\n";
	//}
	}
	
	echo 'Aqui está mais informações de debug:';
	print_r($_FILES);

	print "</pre>";


});

$app->get('/download/servicos/{disp}/{nomearqpast}/{nomearq}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	//$perfil = $req->getAttribute('perfil');
	
	$nomearqpast = $req->getAttribute('nomearqpast');
	
	$nomearq = $req->getAttribute('nomearq');
    
	//$query = "UPDATE servicos SET download = 'S' WHERE nome_servico ='$nomearqpast' AND perfil ='$perfil' AND dispositivo ='$disp'";
	$query = "UPDATE servicos SET download = 'S' WHERE nome_servico ='$nomearqpast' AND dispositivo ='$disp'";
	$result1 = $mysqli->query($query);
	
	echo $query;

	//C:\wamp\www\slimtest\servicos\10\perfil1\1
	
	//$file = 'C:/wamp/www/slimtest/servicos/' . $disp . '/' . $perfil . '/' . $nomearqpast . '/' . $nomearq;
	$file = 'C:/wamp/www/slimtest/servicos/' . $disp . '/' . $nomearqpast . '/' . $nomearq;
	
	echo $file;
	
	header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header("Content-Type: application/octet-stream");
	header("Content-Length: " . filesize($file));
	//echo $file;
	readfile($file);
	
	//necessario a troca do código pelo de cima
 /*  $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
return $response;*/
});

$app->get('/servicos/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	//$perfil = $req->getAttribute('perfil');
	
	$teste = 0;
    
	//$query = "SELECT nome_servico FROM servicos WHERE dispositivo = '$disp' AND perfil = '$perfil' AND download = 'N' LIMIT 1";
	$query = "SELECT nome_servico FROM servicos WHERE dispositivo = '$disp' AND download = 'N' AND servico_disp = '1' LIMIT 1";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["nome_servico"];
	}
	
	//echo $teste;	
	//echo json_encode($data);

	if($teste > 0){
		$response = $teste;
	}else{
		$response = "0";
	}
	
	//echo $response;
	return $response;
	
});

$app->get('/servicos/{disp}/{servico}/scripts', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$servico = $req->getAttribute('servico');

	$teste = '0';
    
	$query = "SELECT COUNT(*) AS qtdescript FROM arquivos_teste INNER JOIN servicos ON arquivos_teste.servico = servicos.nome_servico WHERE servico = 6 AND arquivos_teste.download = 'N' AND servico_disp = '1';";
	$result = $mysqli->query($query);
	
	//echo $query;
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["qtdescript"];
	}

	if($teste != '0'){
		$response = $teste;
	}else{
		$response = "0";
	}

	return $response;
	
});

$app->get('/servicos/{disp}/{servico}/namescripts/name', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$servico = $req->getAttribute('servico');

	$teste = '0';
    
	$query = "SELECT script FROM arquivos_teste INNER JOIN servicos ON arquivos_teste.servico = servicos.nome_servico WHERE servico = 6 AND arquivos_teste.download = 'N' AND servico_disp = '1' LIMIT 1;";
	$result = $mysqli->query($query);
	
	//echo $query;
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["script"];
	}

	if($teste != '0'){
		$response = $teste;
	}else{
		$response = "0";
	}

	return $response;
	
});

$app->get('/servicos/{disp}/{servico}/{script}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$servico = $req->getAttribute('servico');
	
	$script = $req->getAttribute('script');

	$teste = '0';
    
	$query = "SELECT arquivo_final FROM arquivos_teste INNER JOIN servicos ON arquivos_teste.servico = servicos.nome_servico WHERE servico = '$servico' AND arquivos_teste.script = '$script' AND arquivos_teste.download = 'N' AND servico_disp = '1' LIMIT 1;";
	$result = $mysqli->query($query);
	
	//echo $query;
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["arquivo_final"];
	}

	if($teste != '0'){
		$response = $teste;
	}else{
		$response = "0";
	}

	return $response;
	
});

$app->get('/servicos/{disp}/{servico}/{script}/disable', function($req, $res, $args) {

	require_once('dbconnect.php');

	$servico = $req->getAttribute('servico');
	
	$script = $req->getAttribute('script');
    
	$query = "UPDATE arquivos_teste SET download = 'S' WHERE servico ='$servico' AND script ='$script'";
	$result1 = $mysqli->query($query);
	
	$response = "1";

	return $response;
	
});


$app->get('/servicos/{disp}/{servico}/disable/download', function($req, $res, $args) {

	require_once('dbconnect.php');

	$servico = $req->getAttribute('servico');
    
	$query = "UPDATE servicos SET download = 'S' WHERE nome_servico ='$servico'";
	$result1 = $mysqli->query($query);
	
	$response = "1";

	return $response;
	
});

$app->get('/servicos_qt/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	//$perfil = $req->getAttribute('perfil');
	
	$teste = 0;
    
	$query = "SELECT COUNT(*) AS qtde FROM servicos WHERE dispositivo = '$disp' AND download = 'N' AND servico_disp = '1' LIMIT 1";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$teste = $row["qtde"];
	}
	
	//echo $teste;	
	//echo json_encode($data);

	if($teste > 0){
		$response = $teste;
	}else{
		$response = "0";
	}
	
	//echo $response;
	return $response;
	
});

$app->get('/enviar/servicos/{disp}/{nomearqpast}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	//$perfil = $req->getAttribute('perfil');
	
	$nomearqpast = $req->getAttribute('nomearqpast');
	
	//$nomearq = $req->getAttribute('nomearq');
    
	//$query = "UPDATE servicos SET download = 'S' WHERE nome_servico ='$nomearqpast' AND perfil ='$perfil' AND dispositivo ='$disp'";
	$query = "UPDATE servicos SET finalizado = 'S' WHERE nome_servico ='$nomearqpast' AND dispositivo ='$disp'";
	$result1 = $mysqli->query($query);
	
	$response = "1";

	return $response;
	
});


$app->get('/dhcp_1/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
		$teste = 0;
    
	$query = "SELECT DHCP_ip, DHCP_mascara, DHCP_gateway, DHCP_dns FROM dispositivos WHERE pvid = '$disp'";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$DHCP_ip = $row["DHCP_ip"];
		$DHCP_mascara = $row["DHCP_mascara"];
		$DHCP_gateway = $row["DHCP_gateway"];
		$DHCP_dns = $row["DHCP_dns"];
	}
	
	echo "address " . $DHCP_ip;
	
	//return $response;
	
});

$app->get('/dhcp_2/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
		$teste = 0;
    
	$query = "SELECT DHCP_ip, DHCP_mascara, DHCP_gateway, DHCP_dns FROM dispositivos WHERE pvid = '$disp'";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$DHCP_ip = $row["DHCP_ip"];
		$DHCP_mascara = $row["DHCP_mascara"];
		$DHCP_gateway = $row["DHCP_gateway"];
		$DHCP_dns = $row["DHCP_dns"];
	}
	
	echo "netmask ". $DHCP_mascara;
	//return $response;
	
});

$app->get('/dhcp_3/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
		$teste = 0;
    
	$query = "SELECT DHCP_ip, DHCP_mascara, DHCP_gateway, DHCP_dns FROM dispositivos WHERE pvid = '$disp'";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$DHCP_ip = $row["DHCP_ip"];
		$DHCP_mascara = $row["DHCP_mascara"];
		$DHCP_gateway = $row["DHCP_gateway"];
		$DHCP_dns = $row["DHCP_dns"];
	}
	
	echo "gateway ". $DHCP_gateway;

	
	//return $response;
	
});

$app->get('/dhcp_4/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
		$teste = 0;
    
	$query = "SELECT DHCP_ip, DHCP_mascara, DHCP_gateway, DHCP_dns FROM dispositivos WHERE pvid = '$disp'";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$DHCP_ip = $row["DHCP_ip"];
		$DHCP_mascara = $row["DHCP_mascara"];
		$DHCP_gateway = $row["DHCP_gateway"];
		$DHCP_dns = $row["DHCP_dns"];
	}
	
	echo "nameserver ". $DHCP_dns;
	
	//return $response;
	
});

$app->get('/arp_conteudo/{disp}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
    
	$query = "SELECT arp_conteudo FROM arp WHERE pvid = '$disp'";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_assoc()){
		$data[] = $row;	
		$arp_conteudo = $row["arp_conteudo"];
	}
	
	echo nl2br($arp_conteudo);
	
	//return $response;
	
});


$app->get('/dhcp_status/{disp}/{st}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$st = $req->getAttribute('st');
	
	$query = "UPDATE dispositivos SET usou_dhcp = '$st' WHERE pvid = '$disp'";
	$result1 = $mysqli->query($query);
	
	$response = $query;

	return $response;
	
});

$app->post('/arp_rasp', function($req, $res, $args) use ($app){

	$arp_rasp = $req->getParsedBody()['arp_comparacao'];
	$arp_pvid = $req->getParsedBody()['arp_pvid'];
	
	//echo $arp_rasp;
	//echo $arp_pvid;
	
	require_once('dbconnect.php');
	
	$query = "UPDATE arp SET arp_comparacao = '$arp_rasp' WHERE pvid = '$arp_pvid';";
	$result1 = $mysqli->query($query);
	
	$response = "1";

	return $response;
	//$data = $req->getParsedBody();
   // print_r($arp_rasp);

});

$app->run();

?>
