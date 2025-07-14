<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

interface Services
{
	public Session         $session 		{get;}
	public FileManager     $fileManager 	{get;}
	public CallbackManager $callbackManager {get;}
	public SmtpServer      $smtpServer 		{get;}
}