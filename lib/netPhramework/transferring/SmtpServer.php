<?php

namespace netPhramework\transferring;

use netPhramework\core\Environment;

readonly class SmtpServer
{
	private StreamSocket $socket;
	private string $serverName;

	public function __construct(Environment $environment)
	{
		$this->socket = new StreamSocket($environment->smtpServerAddress);
		$this->serverName = $environment->smtpServerName;
	}

	/**
	 * @return $this
	 * @throws SmtpException
	 * @throws StreamSocketException
	 */
	public function connect():self
	{
		$this->verify(SmtpResponseCode::SERVICE_READY, $this->socket
			->open()
			->readCode());
		return $this;
	}

	/**
	 * @return $this
	 * @throws SmtpException
	 */
	public function hello():self
	{
		$this->verify(SmtpResponseCode::OK, $this->socket
			->write("HELO $this->serverName")
			->readCode());
		return $this;
	}

	/**
	 * @param string $sender
	 * @return $this
	 * @throws SmtpException
	 */
	public function sendingFrom(string $sender):self
	{
		$this->verify(SmtpResponseCode::OK, $this->socket
			->write("MAIL FROM: $sender")
			->readCode());
		return $this;
	}

	/**
	 * @param string $recipient
	 * @return $this
	 * @throws SmtpException
	 */
	public function sendingTo(string $recipient):self
	{
		$this->verify(SmtpResponseCode::OK, $this->socket
			->write("RCPT TO: $recipient")
			->readCode());
		return $this;
	}

	/**
	 * @return $this
	 * @throws SmtpException
	 */
	public function start():self
	{
		$this->verify(SmtpResponseCode::START_MAIL_INPUT, $this->socket
			->write("DATA")
			->readCode());
		return $this;

	}

	/**
	 * @return $this
	 * @throws SmtpException
	 */
	public function send():self
	{
		$this->verify(SmtpResponseCode::OK, $this->socket
			->write(".")
			->readCode());
		return $this;
	}

	/**
	 * @return $this
	 * @throws SmtpException
	 */
	public function bye():self
	{
		$this->verify(SmtpResponseCode::GOODBYE, $this->socket
			->write("QUIT")
			->readCode());
		return $this;
	}

	public function disconnect():self
	{
		$this->socket->close();
		return $this;
	}

	public function compose(Email $email):self
	{
		$this->socket
			->write("From: $email->from")
			->write("To:  $email->to")
			->write("Subject: $email->subject")
			->write("MIME-Version: 1.0")
			->write("Content-Type: text/html; charset=$email->charset")
			->write("Content-Transfer-Encoding: 8bit")
			->write('')
			->write($email->message)
		;
		return $this;
	}

	public function getLastMessage():string
	{
		return $this->socket->getLastMessage();
	}

	/**
	 * @param SmtpResponseCode $code
	 * @param int|null $responseCode
	 * @throws SmtpException
	 */
	private function verify(SmtpResponseCode $code, ?int $responseCode):void
	{
		if(!$code->test($responseCode))
			throw new SmtpException("Expected $code->value");
	}
}