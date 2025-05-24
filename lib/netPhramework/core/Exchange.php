<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\Path;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\rendering\View;
use netPhramework\rendering\ViewConfiguration;

/**
 * The central mediator for the Request-Response cycle
 *
 */
interface Exchange
{
	/**
	 * Generate a standard displayable Response to be wrapped.
	 *
	 * @param View $view
	 * @return ViewConfiguration - Passes back the content for additional config
	 */
	public function ok(View $view):ViewConfiguration;

    /**
     * Centralized method for wrapping wrappable content a passing to
     * final display method.
     *
     * @param View $view
     * @param ResponseCode $code
     * @return ViewConfiguration - Passes back the content for additional config
     */
    public function display(
		View $view, ResponseCode $code):ViewConfiguration;

	/**
	 * Configures the Exchange for a Redirection Response
	 *
	 * @param Dispatcher $fallback
	 * @return Variables - mutable parameters for destination ReadableLocation
	 * @throws Exception
	 */
	public function redirect(Dispatcher $fallback):Variables;

	/**
     * Dispatches with an error code and message stored in Session for display
     *
	 * @param Exception $exception
     * @param Dispatcher $fallback
	 * @return void
	 */
	public function error(Exception $exception, Dispatcher $fallback):void;

	/**
	 * Returns a mutable copy of the Path of the originally requested
	 * ReadableLocation
	 *
	 * @return Path
	 */
	public function getPath(): Path;

	/**
	 * Returns a mutable copy of the parameters of the originally requested
	 * ReadableLocation
	 *
	 * @return Variables
	 */
	public function getParameters(): Variables;

	/**
	 * Returns a mutable copy of the originally requested ReadableLocation
	 *
	 * @return Location
	 */
	public function getLocation(): Location;

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
     * If $chain is false (default) Returns any callback ReadableLocation found
	 * in the Exchange Parameters OR the current ReadableLocation if none is
	 * found.
     *
     * If $chain is true, it will insert the current ReadableLocation as the
	 * first in
     * the callback chain and append any callback ReadableLocation found in
	 * the Exchange
     * Parameters.
	 *
     * @param bool $chain - false: defer to existing callback, true: inject
     * current location into callback chain to request a return to this
	 * location
     * first.
     *
	 * @return string|ReadableLocation
	 */
	public function callbackLink(bool $chain = false):string|ReadableLocation;

	/**
	 * A convenience method to generate a Hidden Form Input with callback link
	 *
	 * @param bool $chain - False (default) only uses current ReadableLocation
	 * when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current ReadableLocation. True interjects with current
	 * location
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
