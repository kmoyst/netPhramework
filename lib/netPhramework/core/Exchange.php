<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\locating\Location;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\presentation\HiddenInput;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\View;
use netPhramework\responding\ResponseCode;

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
	 * @return ConfigurableView - Passes back the content for additional config
	 */
	public function ok(View $view):ConfigurableView;

    /**
     * Centralized method for wrapping wrappable content a passing to
     * final display method.
     *
     * @param View $view
     * @param ResponseCode $code
     * @return ConfigurableView - Passes back the content for additional config
     */
    public function display(
		View $view, ResponseCode $code):ConfigurableView;

	/**
	 * @param Redirector $fallback
	 * @return Variables
\	 */
	public function redirect(Redirector $fallback):Variables;

	/**
     * Dispatches with an error code and message stored in Session for display
     *
	 * @param Exception $exception
     * @param Redirector $fallback
	 * @return void
	 */
	public function error(Exception $exception, Redirector $fallback):void;

	/**
	 * Returns a mutable copy of the MutablePath of the originally requested
	 * Location
	 *
	 * @return MutablePath
	 */
	public function getPath(): MutablePath;

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
     * If $chain is false (default) Returns any callback Location found
	 * in the Exchange Parameters OR the current Location if none is
	 * found.
     *
     * If $chain is true, it will insert the current Location as the
	 * first in
     * the callback chain and append any callback Location found in
	 * the Exchange
     * Parameters.
	 *
     * @param bool $chain - false: defer to existing callback, true: inject
     * current location into callback chain to request a return to this
	 * location
     * first.
     *
	 * @return string|Location
	 */
	public function callbackLink(bool $chain = false):string|Location;

	/**
	 * A convenience method to generate a Hidden Form Input with callback link
	 *
	 * @param bool $chain - False (default) only uses current Location
	 * when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current Location. True interjects with current
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

	/**
	 * Manager for uploaded files via form
	 *
	 * @return FileManager
	 */
	public function getFileManager():FileManager;

	/**
	 * Respond with file transfer
	 *
	 * @param File $file
	 * @return void
	 */
	public function transferFile(File $file):void;
}
