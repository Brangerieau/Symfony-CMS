<?php

namespace Brangerieau\SymfonyCmsBundle\Services;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Mailing
{
    public function __construct(
        private readonly Environment $twig,
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @param string               $from
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function create(string $to, string $template, array $data = [], mixed $from = 'noreply@symfonnycms.fr', string $reply = ''): Email
    {
        $this->twig->addGlobal('format', 'text');
        $text = $this->twig->render($template, array_merge($data, ['layout' => 'mails/base.text.twig']));
        $this->twig->addGlobal('format', 'html');
        $html = $this->twig->render($template, array_merge($data, ['layout' => 'mails/base.html.twig']));

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->html($html)
            ->text($text);

        if (!empty($reply)) {
            $email->replyTo($reply);
        }

        return $email;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}
