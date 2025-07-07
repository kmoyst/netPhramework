<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\locating\MutablePath;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\networking\SmtpServer;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\View;
use netPhramework\responding\ResponseCode;

/**
 * The central mediator for the Request-Response cycle
 *
 */
interface Exchange extends CallbackContext
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
	 */
	public function redirect(Redirector $fallback):Variables;

	/**
	 * Respond with file transfer
	 *
	 * @param File $file
	 * @return void
	 */
	public function transferFile(File $file):void;

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
	 * @return string
	 */
	public function getSiteAddress():string;

	/**
	 * @return SmtpServer
	 */
	public function getSmtpServer():SmtpServer;
}
