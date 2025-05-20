<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\dispatching\Dispatchable;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\Location;
use netPhramework\exceptions\Exception;
use netPhramework\rendering\Viewable;
use netPhramework\rendering\Wrappable;
use netPhramework\responding\ResponseCode;

interface Exchange extends Dispatchable
{
	public function ok(Wrappable $content):Exchange;
	public function error(Exception $exception):Exchange;
    public function display(Wrappable $content, ResponseCode $code):Exchange;
	public function displayRaw(Viewable $content, ResponseCode $code):Exchange;
	public function getSession():Session;
	public function callback(Dispatcher $fallback):Exchange;
	public function getCallbackKey():string;
	public function stickyCallback():string|Location;
}
