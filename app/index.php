<?php

require_once __DIR__.'/../vendor/autoload.php';

/**
 * Class teleport site
 */
class iMegaTeleportSite
{
    protected $error = false;
    protected $user = '';
    protected $pass = '';
    protected $debug = false;

    public function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
        set_error_handler(array($this, 'errorHandler'));
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->error = $errno . $errstr . $errfile . $errline;
    }

    /**
     * Send mail
     */
    public function mail($to, $subject, $message, $altMessage = '')
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        if ($this->debug) {
            $mail->SMTPDebug = 4;
        }
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = $this->user;
        $mail->Password = $this->pass;
        $mail->SetFrom('baks@imega.ru', "iMegaTeleport Robot");
        $mail->Subject = "iMegaTeleport. " . $subject;
        $mail->AddAddress($to);
        $mail->msgHTML($message, dirname(__FILE__));
        $mail->AltBody = $altMessage;
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }

    public function template($value)
    {
        return file_get_contents("app/$value.html");
    }
}

$iMegaTeleportSite = new iMegaTeleportSite($_ENV['SMTP_USER'], $_ENV['SMTP_PASS']);
if (!isset($_GET['action'])) {
    echo "Wrong action";
    exit(1);
}
if (!isset($_GET['to'])) {
    echo "Wrong recipient";
    exit(1);
}

$message = '';

if ('activate' == $_GET['action']) {
    $subject = 'Подтвердите email';
    $message = $iMegaTeleportSite->template('activate');
}

if ('account' == $_GET['action']) {
    $subject = 'Ваша учетная запись';
    $message = $iMegaTeleportSite->template('account');
}

$iMegaTeleportSite->mail($_GET['to'], $subject, $message);
