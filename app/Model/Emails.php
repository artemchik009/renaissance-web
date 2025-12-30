<?php

// i'm sorry for shit coding ;)

declare(strict_types=1);

class Emails
{
	private string $smtp_host;
	private string $smtp_username;
	private string $smtp_email;
	private string $smtp_password;
	private string $smtp_encryption;
	private mixed $latte;

    public function __construct()
    {
		$smtp_config = Nette\Neon\Neon::decodeFile($_SERVER['DOCUMENT_ROOT'].'/../config/smtp.neon');

        $this->smtp_host = $smtp_config['smtp_host'];
        $this->smtp_username = $smtp_config['smtp_username'];
        $this->smtp_email = $smtp_config['smtp_email'];
        $this->smtp_password = $smtp_config['smtp_password'];
        $this->smtp_encryption = $smtp_config['smtp_encryption'];

        $this->latte = new Latte\Engine();
        $this->latte->setTempDirectory($_SERVER['DOCUMENT_ROOT']."/temp");
    }

    public function send(string $latte_file, string $to, array $params = []): void
	{
		$html = $this->latte->renderToString($latte_file, $params);

		$mail = new Nette\Mail\Message;
		$mail->setFrom("Renaissance <$this->smtp_email>")->addTo($to)->setSubject("Renaissance - Уведомление")->setHtmlBody($html);
		$mailer = new Nette\Mail\SmtpMailer(
			host: $this->smtp_host,
			username: $this->smtp_username,
			password: $this->smtp_password,
			encryption: $this->smtp_encryption
		);
		$mailer->send($mail);
	}
}