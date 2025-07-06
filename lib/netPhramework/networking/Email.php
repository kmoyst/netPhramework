<?php

namespace netPhramework\networking;

use netPhramework\core\Exception;

class Email
{
	private string $recipientName;
	private string $recipient;
	private string $sender;
	private string $senderName;
	private string $subject;
	private string $message;
	private string $boundary;
	private StreamSocket $socket;

	public function __construct(
		private readonly string $smtpServer = 'ssl://mail.moyst.ca:465',
		private readonly string $sendingServer = 'moyst.ca',
		private readonly string $charset = 'UTF-8'
	)
	{
		$this->boundary = md5(uniqid());
	}

	/**
	 * @return $this
	 * @throws EmailException
	 * @throws Exception
	 * @throws StreamSocketException
	 */
	public function send():self
	{
		if(!$this->isPrepared())
			throw new Exception("Tried to send email unprepared email");
		$this->socket = new StreamSocket($this->smtpServer);
		try {
			$this->verify(SmtpResponseCode::SERVICE_READY,
				$this->socket->open()->readResponseCode());
			$this->verify(SmtpResponseCode::OK, $this->socket
				->write("helo $this->sendingServer")->readResponseCode());
			$this->verify(SmtpResponseCode::OK, $this->socket
				->write("mail from: $this->sender")->readResponseCode());
			$this->verify(SmtpResponseCode::OK, $this->socket
				->write("rcpt to: $this->recipient")->readResponseCode());
			$this->verify(SmtpResponseCode::START_MAIL_INPUT, $this->socket
				->write("data")->readResponseCode())
			;
			$this->writePlainText()
			;
			$this->verify(SmtpResponseCode::OK, $this->socket
				->write('.')->readResponseCode());
			$this->verify(SmtpResponseCode::GOODBYE, $this->socket
				->write('quit')->readResponseCode());
		} catch (SmtpException $e) {
			$msg = $e->getMessage() . ', ' . $this->socket->getLastMessage();
			throw new SmtpException(trim($msg,', '));
		} finally {
			$this->socket->close();
		}
		return $this;
	}

	private function writePlainText():void
	{
		$this->socket
			->write("From: " . $this->resolveSenderName())
			->write("To: " . $this->resolveRecipientName())
			->write("Subject: $this->subject")
			->write("MIME-Version: 1.0")
			->write("Content-Type: text/plain; charset=$this->charset")
			->write("Content-Transfer-Encoding: 8bit")
			->write('')
			->write($this->message ?? '')
			;
	}

	/**
	 * @param SmtpResponseCode $code
	 * @param int|null $responseCode
	 * @return void
	 * @throws SmtpException
	 */
	private function verify(SmtpResponseCode $code, ?int $responseCode):void
	{
		if(!$code->test($responseCode))
			throw new SmtpException("Expected $code->value");
	}

	private function isPrepared():bool
	{
		return isset($this->sender) && isset($this->recipient)
			&& isset($this->subject);
	}

	private function resolveSenderName():string
	{
		if(isset($this->senderName))
			return mb_encode_mimeheader(
				"$this->senderName <$this->sender>", $this->charset);
		else return $this->sender;
	}

	private function resolveRecipientName():string
	{
		if(isset($this->recipientName))
			return mb_encode_mimeheader(
				"$this->recipientName <$this->recipient>");
		else
			return $this->recipient;
	}

	// SETTER METHODS //

	public function setRecipient(string $recipient): self
	{
		$this->recipient = $recipient;
		return $this;
	}

	public function setRecipientName(string $recipientName): self
	{
		$this->recipientName = $recipientName;
		return $this;
	}

	public function setSender(string $sender): self
	{
		$this->sender = $sender;
		return $this;
	}

	public function setSenderName(string $senderName): self
	{
		$this->senderName = $senderName;
		return $this;
	}

	public function setSubject(string $subject): Email
	{
		$this->subject = $subject;
		return $this;
	}

	public function setMessage(string $message): Email
	{
		$this->message = $message;
		return $this;
	}
}