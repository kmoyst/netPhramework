<?php

namespace netPhramework\presentation;

use netPhramework\user\Session;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use netPhramework\rendering\ImmutableView;
use Stringable;

readonly class FeedbackView implements Encodable
{
	public function __construct(private Session $session) {}

	public function encode(Encoder $encoder): Stringable|string
	{
		$message = $this->session->getFeedbackAndClear();
		if($message === null) return '';
		$view = new ImmutableView('error-message', ['message' => $message]);
		return $encoder->encodeViewable($view);
	}
}