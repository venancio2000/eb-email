<?php

	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	
	
	class Mensagem {
		private $para = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array('codigo_status' => null, 'descricao_status' => '');

		public function __get($atributo) {
			return $this->$atributo;
		}

		public function __set($atributo, $valor) {
			$this->$atributo = $valor;
		}

		public function mensagemValida() {
			if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
				return false;
			}

			return true;
		}
	}

	$mensagem = new Mensagem();

	$mensagem->__set('para', $_POST['para']);
	$mensagem->__set('assunto', $_POST['assunto']);
	$mensagem->__set('mensagem', $_POST['mensagem']);

	//print_r($mensagem);
	
	if(!$mensagem->mensagemValida()) {
		echo 'mensagem nao e valida';
		header('Location: index.php');
	} 

	$mail = new PHPMailer(true);
	try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'brunovenancio589@gmail.com';                 // SMTP username
    $mail->Password = '91801207';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('jbrunogvenancio@gmail.com', 'bruno venancio');
    $mail->addAddress($mensagem->__get('para'));     // Add a 
    $mail->addReplyTo('brunovenancio589@gmail.com', 'enviado');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'E necessario utilizar um client quw surpota html';

    $mail->send();
    $mensagem->status['codigo_status'] =  1;
    $mensagem->status['descricao_status'] =  'e-mail enviado com sucesso';
	
	} catch (Exception $e) {
		$mensagem->status['codigo_status'] =  2;
    	$mensagem->status['descricao_status'] =  'Não foi possíviel enviar este e-mail! Por favor tente novamente' . $mail->ErrorInfo ;

	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>app</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
				<div class="row">
					<div class="col-md-12">
						<? if ($mensagem->status['codigo_status'] == 1) {?>
								<div class="container">
									<h1 class="dilpay-4 text-success">Sucesso</h1>
									<p><?= $mensagem->status['descricao_status']?><a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a></p>
								</div>

						<? } ?>
						<? if ($mensagem->status['codigo_status'] == 2) {?>
								<div class="container">
									<h1 class="dilpay-4 text-danger">ERRO</h1>
									<p><?= $mensagem->status['descricao_status']?><a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a></p>
								</div>

						<? } ?>
							
						
					</div>
				</div>
			</div>
			
		</div>
</body>
</html>
	