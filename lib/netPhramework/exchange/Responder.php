<?php

namespace netPhramework\exchange;

use netPhramework\common\FileFinder;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Wrappable;
use netPhramework\rendering\Wrapper;
use netPhramework\transferring\File;

interface Responder
{
	public Encoder $encoder {get;}
	public Wrapper $wrapper {get;}
	public FileFinder $templateFinder {get;}

	public function present(Wrappable $content, ResponseCode $code): void;
	public function redirect(Encodable $content, ResponseCode $code): void;
	public function transfer(File $file, ResponseCode $code): void;
}