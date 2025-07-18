<?php

namespace netPhramework\cli;

use netPhramework\common\FileFinder;
use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Gateway;
use netPhramework\exchange\Responder;
use netPhramework\exchange\ResponseCode;
use netPhramework\exchange\Services;
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

	/**
	 * @param Wrappable $content
	 * @param ResponseCode $code
	 * @return void
	 * @throws Exception
	 * @throws NodeNotFound
	 */
	public function present(Wrappable $content, ResponseCode $code): void
	{
		echo $this->wrapper
			->wrap($content)
			->encode($this->configure()->encoder);
		$this->newQuery();
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
		$feedback = $this->services->session->getFeedbackAndClear();
		if($feedback !== null) echo "\n\n$feedback\n\n";
		$this->newQuery();
	}

	/**
	 * @return void
	 * @throws Exception
	 * @throws NodeNotFound
	 */
	private function newQuery():void
	{
		$question  = "\n\nWould you like to make another request?\n";
		$question .= "(q to quit, any other key to continue): ";
		if(readline($question) === 'q') exit(0);
		$request = new CliRequest($this->environment);
		new Gateway($this->application)
			->mapToRouter($request->isModificationRequest)
			->route($request->location)
			->openExchange($this->services)
			->execute($this->environment)
			->deliver($this);
	}

	public function transfer(File $file, ResponseCode $code): void
	{

	}
}