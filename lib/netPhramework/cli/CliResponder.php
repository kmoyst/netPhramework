<?php

namespace netPhramework\cli;

use netPhramework\common\FileFinder;
use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\Navigator;
use netPhramework\exchange\Responder;
use netPhramework\exchange\ResponseCode;
use netPhramework\exchange\Services;
use netPhramework\nodes\Directory;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\Location;
use netPhramework\transferring\File;

class CliResponder implements Responder
{
	public Environment $environment;
	public Application $application;
	public Services $services;

	public function __construct
	(
	public Encoder $encoder = new Encoder(),
	public Wrapper $wrapper = new Wrapper(),
	public FileFinder $templateFinder = new FileFinder(),
	) {}

	private function configure():self
	{
		$this->encoder->setTemplateFinder($this->templateFinder);
		return $this;
	}

	public function present(Wrappable $content, ResponseCode $code): void
	{
		echo $this->wrapper->wrap($content)->encode($this->configure()->encoder);
	}

	/**
	 * @param Location $location
	 * @param ResponseCode $code
	 * @return void
	 * @throws Exception
	 * @throws NodeNotFound
	 */
	public function redirect(Location $location, ResponseCode $code): void
	{
		$root = new Directory('');
		$this->application->configurePassiveNode($root);
		$handler = new Navigator()->setRoot($root)
			->setPath($location->path)->navigate();
		$exchange = new Exchange($location)
			->setEnvironment($this->environment)
			->setSession($this->services->session)
			->setSmtpServer($this->services->smtpServer)
			->setFileManager($this->services->fileManager)
			->setCallbackManager($this->services->callbackManager)
		;
		$handler->handleExchange($exchange);
		$exchange->response->deliver($this);
	}

	public function transfer(File $file, ResponseCode $code): void
	{

	}
}