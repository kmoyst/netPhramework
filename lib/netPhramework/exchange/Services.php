<?php

namespace netPhramework\exchange;

use netPhramework\user\Session;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

readonly class Services
{
	public function __construct(
		public Session         $session,
		public FileManager     $fileManager,
		public CallbackManager $callbackManager,
		public SmtpServer      $smtpServer,
	) {}
}