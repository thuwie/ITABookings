<?php

namespace App\Domain\Entity;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    private string $host;
    private string $username;
    private string $password;
    private int $port;
    private string $fromEmail;
    private string $fromName;
    private string $encryption;

    public function __construct(
        string $host,
        string $username,
        string $password,
        int $port = 587,
        string $fromEmail = 'no-reply@ITABookings.com',
        string $fromName = 'ITABookings',
        string $encryption = 'tls'
    ) {
        $this->host      = $host;
        $this->username  = $username;
        $this->password  = $password;
        $this->port      = $port;
        $this->fromEmail = $fromEmail;
        $this->fromName  = $fromName;
        $this->encryption = $encryption;
    }

    public function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $this->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $this->username;
        $mail->Password   = $this->password;
        $mail->SMTPSecure = $this->encryption;
        $mail->Port       = $this->port;

        $mail->setFrom($this->fromEmail, $this->fromName);
        $mail->isHTML(true);

        return $mail;
    }
}
