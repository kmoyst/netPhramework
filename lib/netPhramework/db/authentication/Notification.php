<?php

namespace netPhramework\db\authentication;

use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\transferring\EmailDelivery;
use netPhramework\transferring\EmailException;
use netPhramework\transferring\StreamSocketException;

class Notification
{

	public function __construct(private readonly NotificationInfo $info) {}
	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws EmailException
	 * @throws StreamSocketException
	 */
	public function notify(Exchange $exchange):void
	{
		new EmailDelivery($exchange->smtpServer)
			->setRecipient($this->info->recipient)
			->setRecipientName($this->info->recipientName)
			->setSender($this->info->sender)
			->setSenderName($this->info->senderName)
			->setSubject($this->info->subject)
			->setMessage($this->info->message)
			->send()
		;
	}
}