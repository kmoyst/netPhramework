<?php

namespace netPhramework\exchange;

use netPhramework\exchange\host\HostMode;
use netPhramework\user\Session;
use netPhramework\transferring\FileManager;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\SmtpServer;

interface Host
{
	public HostMode $mode {get;}
	public Request $request {get;}
	public Responder $responder {get;}
	public Session $session {get;}
	public FileManager $fileManager {get;}
	public CallbackManager $callbackManager {get;}
	public SmtpServer $smtpServer {get;}

	public string $domain {get;}
	public string $protocol {get;}

	public function configureResponder(Responder $responder):void;
}