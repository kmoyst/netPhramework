<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

class HttpServices implements Services
{
	public function __construct(
		public Session         $session 		= new Session(),
		public FileManager     $fileManager 	= new FileManager(),
		public CallbackManager $callbackManager = new CallbackManager(),
		public SmtpServer      $smtpServer 		= new SmtpServer(),
	) {}
}