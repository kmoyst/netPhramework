<?php

namespace netPhramework\transferring;

readonly class SmtpServer
{
	private StreamSocket $socket;

	public function __construct(
		string $serverAddress,
		private string $serverName)
	{
		$this->socket = new StreamSocket($serverAddress);
	}

	/**
	 * Connects to server
	 *
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
	 * Sends HELO command to server
	 *
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
	 * Tells server who the email is from
	 *
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
	 * Tells server the addressee
	 *
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
	 * Sends signal to start composing email
	 *
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
	 * Ends composition, email to be sent
	 *
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
	 * Sends QUIT command to server
	 *
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

	/**
	 * Closes socket to server
	 *
	 * @return $this
	 */
	public function disconnect():self
	{
		$this->socket->close();
		return $this;
	}

	/**
	 * Composes the email on the server based on Email provided
	 *
	 * @param Email $email
	 * @return $this
	 */
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

	/**
	 * Returns last message received from server.
	 *
	 * @return string
	 */
	public function getLastMessage():string
	{
		return $this->socket->getLastMessage();
	}

	/**
	 * Verifies expected response code was received.
	 *
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