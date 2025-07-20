<?php

namespace netPhramework\exchange;

use netPhramework\common\FileFinder;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\routing\Location;
use netPhramework\transferring\File;
use netPhramework\core\Application;

interface Responder
{
	public Application $application {get;set;}
	public Services $services{get;set;}
	public Encoder $encoder {get;}
	public Wrapper $wrapper {get;}
	public FileFinder $templateFinder {get;}
	public string $siteAddress {get;set;}
	public function present(Wrappable $content, ResponseCode $code): void;
	public function redirect(Location $location, ResponseCode $code): void;
	public function transfer(File $file, ResponseCode $code): void;

	public function setApplication(Application $application):self;
	public function setServices(Services $services):self;
}