<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\site\Application;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;
use netPhramework\bootstrap\Environment;

interface RequestContext
{
	public Environment $environment { get; }
	public CallbackManager $callbackManager  { get; }
	public Session $session {get;}
	public FileManager $fileManager {get;}
	public SmtpServer $smtpServer{get;}

	public function getApplication():Application;
}