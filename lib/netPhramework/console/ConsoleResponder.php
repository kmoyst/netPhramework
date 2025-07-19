<?php

namespace netPhramework\console;

use netPhramework\common\FileFinder;
use netPhramework\core\Application;
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
use netPhramework\user\Session;

class ConsoleResponder implements Responder
{
	public Application $application;
	public Services $services;
	public Session $session;
	public Encoder $encoder;
	public Wrapper $wrapper;
	public FileFinder $templateFinder;
	public string $siteAddress;

	public function setSession(Session $session): self
	{
		$this->session = $session;
		return $this;
	}

	public function setEncoder(Encoder $encoder): self
	{
		$this->encoder = $encoder;
		return $this;
	}

	public function setWrapper(Wrapper $wrapper): self
	{
		$this->wrapper = $wrapper;
		return $this;
	}

	public function setTemplateFinder(FileFinder $templateFinder): self
	{
		$this->templateFinder = $templateFinder;
		return $this;
	}

	public function setSiteAddress(string $siteAddress): self
	{
		$this->siteAddress = $siteAddress;
		return $this;
	}

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
	 */
	public function redirect(Location $location, ResponseCode $code): void
	{
		$feedback = $this->session->getFeedbackAndClear();
		if($feedback !== null) echo "\n\n$feedback\n\n";
		try { // some redirects will work
			new Gateway($this->application)
				->mapToRouter(false)
				->route($location)
				->openExchange($this->services)
				->execute($this->siteAddress)
				->deliver($this);
		} catch (NodeNotFound) {} // others won't
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
		$request = new ConsoleRequest();
		new Gateway($this->application)
			->mapToRouter($request->isToModify)
			->route($request->location)
			->openExchange($this->services)
			->execute($this->siteAddress)
			->deliver($this);
	}

	public function transfer(File $file, ResponseCode $code): void
	{

	}
}