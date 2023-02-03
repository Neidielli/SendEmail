<?php

    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php"; // protocolo de recebimento de email
    require "./bibliotecas/PHPMailer/SMTP.php"; // protocolo de envio de email

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $destino = null;
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
            // verifica se os atributos estão preenchidos
            if(empty($this->destino) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem(); // instancia a classe
    // preenchimento do obj instanciado
    $mensagem->__set('destino', $_POST['destino']); 
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    // recupera a instancia do obj mensagem e executa o método mensagemValida
    if(!$mensagem->mensagemValida()) {
        echo 'Mensagem não é válida';
        header('Location: index.php'); // passar parametro na url para informar o usuario que há informações faltantes
    } 

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                                       //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'seuemail@gmail.com';                   //SMTP username - Utilizar gmail existente
        $mail->Password   = 'senhagerada';                          //SMTP password - Senha gerada pelo gmail
        $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('seuemail@gmail.com', 'Seu Nome'); // email remetente
        $mail->addAddress($mensagem->__get('destino'));           //Add a recipient         
        // $mail->addReplyTo('', ''); // responder para uma terceira pessoa
        // $mail->addCC('cc@example.com'); // Destinatarios  de cópias
        // $mail->addBCC('bcc@example.com'); // Destinatarios de cópia oculta

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');               //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');          //Optional name

        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'É necessário utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem';

        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';
        
    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possível enviar este e-mail! Por favor tente novamente. Detalhes do erro: ' . $mail->ErrorInfo;

    }
?>

<html>
    <head>
        <meta charset="utf-8" />
    	<title>SendEMail</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>

    <body>

        <div class="container">

            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="aviao.png" alt="" width="72" height="72">
				<h2 style="color: #1E2469;">Send Email</h2>
				<p class="lead" style="margin-bottom: 50px;">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">

                    <?php if($mensagem->status['codigo_status'] == 1) { ?>

                        <div class="container text-center"> 
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?>

                    <?php if($mensagem->status['codigo_status'] == 2) { ?>
                        
                        <div class="container text-center"> 
                            <h1 class="display-4 text-danger">Ops!! Algo não ocorreu bem.</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?>

                </div>
            </div>
        </div>
    </body>
</html>