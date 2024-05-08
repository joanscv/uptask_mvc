<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email { 

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email  = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        //$mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->addAddress($this->email);
        $mail->Subject = "Confirma tu cuenta";

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola, " . $this->nombre . "</strong>. Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace:</p>";
        $contenido .= "<p>Presiona aquí: <a href='https://uptask-js.alwaysdata.net/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';
        
        $mail->Body = $contenido;
        $mail->send();

    }

    public function enviarInstuccionesCambioPassword() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = "Restablece tu Password";

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola, " . $this->nombre . "</strong>. A través del siguiente enlace podrás cambiar la constraseña de tu cuenta en UpTask:<p>";
        $contenido .= "<p>Presiona aquí: <a href='https://uptask-js.alwaysdata.net/restablecer?token=" . $this->token . "'>Restablecer Password</a></p>";
        //$contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';
        
        $mail->Body = $contenido;
        $mail->send();

    }
}