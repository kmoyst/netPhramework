<?php

namespace netPhramework\networking;

use netPhramework\exceptions\Exception;

class EmailDelivery
{
	private readonly string $charset;
	private readonly string $boundary;

	private string $recipient;
	private ?string $recipientName;
	private string $sender;
	private ?string $senderName;
	private string $subject;
	private string $message;

	public function __construct(private readonly SmtpServer $server)
	{
		$this->boundary = md5(uniqid());
		$this->charset = 'UTF-8';
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
			throw new Exception("Tried to send email unprepared email")
		;
		try {
			$this->server
				->connect()
				->hello()
				->sendingFrom($this->sender)
				->sendingTo($this->recipient)
				->start()
				->compose($this->generateEmail())
				->send()
				->bye();
		} catch (SmtpException $e) {
			$msg = $e->getMessage() . ', ' . $this->server->getLastMessage();
			throw new SmtpException(trim($msg,', '));
		} finally {
			$this->server->disconnect();
		}
		return $this;
	}

	private function generateEmail():Email
	{
		return new Email(
			$this->resolveSenderName(),
			$this->resolveRecipientName(),
			$this->subject,
			$this->message ?? '',
			$this->charset
		);
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
				"\"$this->senderName\" <$this->sender>", $this->charset);
		else return $this->sender;
	}

	private function resolveRecipientName():string
	{
		if(isset($this->recipientName))
			return mb_encode_mimeheader(
				"\"$this->recipientName\" <$this->recipient>");
		else
			return $this->recipient;
	}

	// SETTER METHODS //

	public function setSender(string $sender): self
	{
		$this->sender = $sender;
		return $this;
	}

	public function setRecipient(string $recipient): self
	{
		$this->recipient = $recipient;
		return $this;
	}

	public function setSubject(string $subject): self
	{
		$this->subject = $subject;
		return $this;
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;
		return $this;
	}

	public function setRecipientName(?string $recipientName): self
	{
		$this->recipientName = $recipientName;
		return $this;
	}

	public function setSenderName(?string $senderName): self
	{
		$this->senderName = $senderName;
		return $this;
	}
}