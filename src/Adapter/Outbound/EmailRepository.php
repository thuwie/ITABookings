<?php

namespace App\Adapter\Outbound;

use App\Application\Port\Outbound\EmailRepositoryPort;
use App\Domain\Entity\MailerService;
use Psr\Http\Message\UploadedFileInterface;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\FileHelper;

class EmailRepository implements EmailRepositoryPort {
     private MailerService $mailerService;

     public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }
    public function providerEmailSending($emailer): bool {

        $emailUser = $emailer['email'];
        $userName =  $emailer['userName'];
        $userId = $emailer['userId'];
        $approvedAt = $emailer['approvedAt'];

        $date = new \DateTime($approvedAt);
        $formatted = $date->format('d/m/Y');

        ob_start();
       include __DIR__ . '/../../templates/pages/email/email_provider_approval_template.php';
        $emailBody = ob_get_clean();

        // Send email using PHP mail()
        $mail = $this->mailerService->createMailer();
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // <-- important!

        try {
            $mail->addAddress($emailUser);
            $mail->Subject = "Đơn đăng ký doanh nghiệp của bạn đã được duyệt!";;
            $mail->Body    = $emailBody;

            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }

        
    }

    public function driverEmailSending($emailer): bool {
        
        $emailUser = $emailer['email'];
        $userName =  $emailer['userName'];
        $approvedAt = $emailer['approvedAt'];
        $businessName = $emailer['businessName'];

        $date = new \DateTime($approvedAt);
        $formatted = $date->format('d/m/Y');

        ob_start();
       include __DIR__ . '/../../templates/pages/email/email_driver_approval_template.php';
        $emailBody = ob_get_clean();

        // Send email using PHP mail()
        $mail = $this->mailerService->createMailer();
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // <-- important!

        try {
            $mail->addAddress($emailUser);
            $mail->Subject = "Đơn đăng ký tài xế của bạn đã được duyệt!";;
            $mail->Body    = $emailBody;

            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }

    }
}
