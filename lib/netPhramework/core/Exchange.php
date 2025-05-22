<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\MutableLocation;
use netPhramework\dispatching\Path;
use netPhramework\exceptions\Exception;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\rendering\Wrappable;

interface Exchange
{
	/**
	 * Generate a standard displayable Response to be wrapped.
	 *
	 * @param Wrappable $content
	 * @return self
	 */
	public function ok(Wrappable $content):self;

	/**
	 * Configures the Exchange for a Redirectable Response
	 *
	 * @param Dispatcher $fallback
	 * @return $this
	 * @throws Exception
	 */
	public function dispatch(Dispatcher $fallback):self;

	/**
	 * Uses custom Exception class to create a Displayable response.
	 *
	 * @param Exception $exception
	 * @return $this
	 */
	public function error(Exception $exception):self;

	/**
	 * Returns a mutable copy of the Path of the originally requested Location
	 *
	 * @return Path
	 */
	public function getRequestPath(): Path;

	/**
	 * Returns a mutable copy of the parameters of the originally requested
	 * Location
	 *
	 * @return Variables
	 */
	public function getRequestParameters(): Variables;

	/**
	 * Returns a mutable copy of the originally requested Location
	 *
	 * @return MutableLocation
	 */
	public function getRequestLocation(): MutableLocation;

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
	 * @param bool $chain - False (default) only uses current location when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current Location. True interjects with current location
	 * even when callback is present. It propagates the existing callback to
	 * allows that information to be preserved upon return to the current
	 * location.
	 *
	 * @return string|Location
	 */
	public function callbackLink(bool $chain = false):string|Location;

	/**
	 * A convenience method to create a form Input from callbackLink
	 *
	 * @param bool $chain
	 * @return HiddenInput
	 */
	public function callbackFormInput(bool $chain = false):HiddenInput;

	/**
	 * Returns current Session
	 *
	 * @return Session
	 */
	public function getSession():Session;

}
