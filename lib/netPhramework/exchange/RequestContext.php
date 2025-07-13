<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\core\Environment;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

interface RequestContext
{
	public Environment $environment { get; }
	public CallbackManager $callbackManager  { get; }
	public Session $session {get;}
	public FileManager $fileManager {get;}
	public SmtpServer $smtpServer{get;}

	public function getApplication():Router;
}