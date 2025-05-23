<?php

namespace netPhramework\core;
use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\dispatching\UriFromLocation;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\Viewable;

readonly class Responder
{
	public function __construct(private Encoder $encoder) {}

	/**
	 * Displays viewable content. Usually from an Exception or a DisplayableContent
	 * Response.
	 *
	 * @param Viewable $content
	 * @param ResponseCode $code
	 * @return void
	 */
	public function display(Viewable $content, ResponseCode $code):void
    {
        http_response_code($code->value);
        echo $this->encoder->encodeViewable($content);
    }

    public function redirect(ReadableLocation $location, ResponseCode $code):void
    {
        http_response_code($code->value);
        header("Location: " . new UriFromLocation($location));
    }
}