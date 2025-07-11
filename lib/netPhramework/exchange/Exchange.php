<?php

namespace netPhramework\exchange;

use netPhramework\authentication\Session;
use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;
use netPhramework\rendering\ConfigurableView;
use netPhramework\rendering\View;
use netPhramework\routing\CallbackContext;
use netPhramework\routing\Location;
use netPhramework\routing\MutablePath;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\transferring\File;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

/**
 * The central mediator for the Request-Response cycle
 *
 */
interface Exchange extends CallbackContext
{
	public MutablePath $path {get;}
	public Variables $parameters {get;}
	public Location $location {get;}
	public Session $session {get;}
	public FileManager $fileManager {get;}
	public string $siteAddress {get;}
	public SmtpServer $smtpServer {get;}

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
}
