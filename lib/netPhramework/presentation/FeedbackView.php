<?php

namespace netPhramework\presentation;

use netPhramework\core\Exchange;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\ImmutableView;
use Stringable;

readonly class FeedbackView implements Encodable
{
	public function __construct(private Exchange $exchange) {}

	public function encode(Encoder $encoder): Stringable|string
	{
		try {
			$message = $this->exchange->getSession()->getErrorMessageAndClear();
			if($message === null) return '';
			$view = new ImmutableView('error-message', ['message' => $message]);
			return $encoder->encodeViewable($view);
		} catch (InvalidSession $e) {
			return '';
		}
	}
}