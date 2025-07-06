<?php

namespace netPhramework\networking;

use netPhramework\core\Exception;

class EmailDelivery
{
	public readonly string $charset;
	public readonly string $boundary;

	private string $recipient;
	private string $recipientName;
	private string $sender;
	private string $senderName;
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
				->openConnection()
				->hello()
				->sendingFrom($this->sender)
				->sendingTo($this->recipient)
				->start()
				->writePlainText($this)
				->send()
				->bye();
		} catch (SmtpException $e) {
			$msg = $e->getMessage() . ', ' . $this->server->getLastMessage();
			throw new SmtpException(trim($msg,', '));
		} finally {
			$this->server->closeConnection();
		}
		return $this;
	}

	private function isPrepared():bool
	{
		return isset($this->sender) && isset($this->recipient)
			&& isset($this->subject);
	}

	public function resolveSenderName():string
	{
		if(isset($this->senderName))
			return mb_encode_mimeheader(
				"\"$this->senderName\" <$this->sender>", $this->charset);
		else return $this->sender;
	}

	public function resolveRecipientName():string
	{
		if(isset($this->recipientName))
			return mb_encode_mimeheader(
				"\"$this->recipientName\" <$this->recipient>");
		else
			return $this->recipient;
	}

	public function getSubject(): string
	{
		return $this->subject;
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

	public function setRecipientName(string $recipientName): self
	{
		$this->recipientName = $recipientName;
		return $this;
	}

	public function setSenderName(string $senderName): self
	{
		$this->senderName = $senderName;
		return $this;
	}
}