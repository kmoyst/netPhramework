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
	 * Configures the Exchange for a RedirectableContent Response
	 *
	 * @param Dispatcher $fallback
	 * @return $this
	 * @throws Exception
	 */
	public function redirect(Dispatcher $fallback):self;

	/**
	 * Uses custom Exception class as a Response. This is displayed.
     *
     * @TODO Implement a way to redirect with message and error code.
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
	public function getPath(): Path;

	/**
	 * Returns a mutable copy of the parameters of the originally requested
	 * Location
	 *
	 * @return Variables
	 */
	public function getParameters(): Variables;

	/**
	 * Returns a mutable copy of the originally requested Location
	 *
	 * @return MutableLocation
	 */
	public function getLocation(): MutableLocation;

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
     * If $chain is false (default) Returns any callback Location found in the
     * Exchange Parameters OR the current Location if none is found.
     *
     * If $chain is true, it will insert the current Location as the first in
     * the callback chain and append any callback Location found in the Exchange
     * Parameters.
	 *
     * @param bool $chain - false: defer to existing callback, true: inject
     * current location into callback chain to request a return to this location
     * first.
     *
	 * @return string|Location
	 */
	public function callbackLink(bool $chain = false):string|Location;

	/**
	 * A convenience method to generate a Hidden Form Input with callback link
	 *
	 * @param bool $chain - False (default) only uses current Location when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current Location. True interjects with current location
	 * even when callback is present. It propagates the existing callback to
	 * allow that information to be preserved upon return to the current
	 * location.
	 *
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
