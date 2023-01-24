<?php

    class Mensagem {
        private $destino = null;
        private $assunto = null;
        private $mensagem = null;

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
    if($mensagem->mensagemValida()) {
        echo 'Mensagem válida';
    } else {
        echo 'Mensagem nao válida';
    }