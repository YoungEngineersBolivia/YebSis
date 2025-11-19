<?php

namespace App\Mail\Transport;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\RawMessage;

class BrevoTransport implements TransportInterface
{
    protected TransactionalEmailsApi $api;

    public function __construct(string $apiKey)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $this->api = new TransactionalEmailsApi(null, $config);
    }
    
    public function send(RawMessage $message, ?object $envelope = null): ?SentMessage
    {
        $this->doSend($message);
        
        return new SentMessage($message, $envelope ?? new \Symfony\Component\Mime\Address('noreply@example.com'));
    }

    protected function doSend(RawMessage $message): void
    {
        $email = MessageConverter::toEmail($message);
        
        $sendSmtpEmail = new SendSmtpEmail();
        
        // Remitente
        $from = $email->getFrom()[0];
        $sendSmtpEmail->setSender([
            'name' => $from->getName(),
            'email' => $from->getAddress()
        ]);
        
        // Destinatarios
        $to = [];
        foreach ($email->getTo() as $recipient) {
            $to[] = [
                'email' => $recipient->getAddress(),
                'name' => $recipient->getName() ?: $recipient->getAddress()
            ];
        }
        $sendSmtpEmail->setTo($to);
        
        // CC (si existe)
        if ($email->getCc()) {
            $cc = [];
            foreach ($email->getCc() as $recipient) {
                $cc[] = [
                    'email' => $recipient->getAddress(),
                    'name' => $recipient->getName()
                ];
            }
            $sendSmtpEmail->setCc($cc);
        }
        
        // BCC (si existe)
        if ($email->getBcc()) {
            $bcc = [];
            foreach ($email->getBcc() as $recipient) {
                $bcc[] = [
                    'email' => $recipient->getAddress(),
                    'name' => $recipient->getName()
                ];
            }
            $sendSmtpEmail->setBcc($bcc);
        }
        
        // Asunto
        $sendSmtpEmail->setSubject($email->getSubject());
        
        // Contenido
        if ($email->getHtmlBody()) {
            $sendSmtpEmail->setHtmlContent($email->getHtmlBody());
        }
        
        if ($email->getTextBody()) {
            $sendSmtpEmail->setTextContent($email->getTextBody());
        }
        
        // Adjuntos (si existen)
        if ($email->getAttachments()) {
            $attachments = [];
            foreach ($email->getAttachments() as $attachment) {
                $attachments[] = [
                    'content' => base64_encode($attachment->getBody()),
                    'name' => $attachment->getName()
                ];
            }
            $sendSmtpEmail->setAttachment($attachments);
        }
        
        // Enviar
        try {
            $this->api->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            throw new \Exception('Error al enviar correo con Brevo: ' . $e->getMessage());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}