<?php

require 'vendor/autoload.php';

$app = new Slim\App();

$container = $app->getContainer();
$container['upload_directory'] = 'C:/wamp/www/slimtest/uploads';

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


$app->post('/uploadarq', function(Request $request, Response $response) {
    $directory = $this->get('upload_directory');

    $uploadedFiles = $request->getUploadedFiles();

    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['example1'];
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedFile($directory, $uploadedFile);
        $response->write('uploaded ' . $filename . '<br/>');
    }


    // handle multiple inputs with the same key
    foreach ($uploadedFiles['example2'] as $uploadedFile) {
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($directory, $uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }
    }

    // handle single input with multiple file uploads
    foreach ($uploadedFiles['example3'] as $uploadedFile) {
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($directory, $uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }
    }

});


/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploaded file uploaded file to move
 * @return string filename of moved file
 */
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->get('/download/servicos/{disp}/{perfil}/{nomearqpast}/{nomearq}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$perfil = $req->getAttribute('perfil');
	
	$nomearqpast = $req->getAttribute('nomearqpast');
	
	$nomearq = $req->getAttribute('nomearq');
    
	$query = "UPDATE servicos SET download = 'S' WHERE nome_servico ='$nomearqpast' AND perfil ='$perfil' AND dispositivo ='$disp'";
	$result1 = $mysqli->query($query);
	
	echo $query;

	//C:\wamp\www\slimtest\servicos\10\perfil1\1
	
	$file = 'C:/wamp/www/slimtest/servicos/' . $disp . '/' . $perfil . '/' . $nomearqpast . '/' . $nomearq;
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


$app->get('/servicos/{disp}/{perfil}', function($req, $res, $args) {

	require_once('dbconnect.php');

	$disp = $req->getAttribute('disp');
	
	$perfil = $req->getAttribute('perfil');
	
	$teste = 0;
    
	$query = "SELECT nome_servico FROM servicos WHERE dispositivo = '$disp' AND perfil = '$perfil' AND download = 'N' LIMIT 1";
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

$app->run();

?>
