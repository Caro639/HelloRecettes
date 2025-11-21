<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    /**
     * Undocumented variable
     */
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        string $from,
        string $subject,
        string $htmlTemplate,
        array $context,
        string $to = 'admin@hellorecettes.com'
    ): void {
        $email = (new TemplatedEmail(
        ))
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            // ->text('Sending emails is fun again!')
            // ->html($contact->getMessage());
            // path of the Twig template to render
            ->htmlTemplate($htmlTemplate)

            // pass variables (name => value) to the template
            ->context([
                'context' => $context
            ]);

        $this->mailer->send($email);
    }
}