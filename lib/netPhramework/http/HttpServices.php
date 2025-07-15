<?php

namespace netPhramework\http;

use netPhramework\exchange\Services;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;
use netPhramework\user\Session;

class HttpServices implements Services
{
	public function __construct(
		public Session         $session 		= new Session(),
		public FileManager     $fileManager 	= new FileManager(),
		public CallbackManager $callbackManager = new CallbackManager(),
		public SmtpServer      $smtpServer 		= new SmtpServer(),
	) {}
}