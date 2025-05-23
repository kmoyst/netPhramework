<?php

namespace netPhramework\core;

use netPhramework\authentication\SessionUser;
use netPhramework\authentication\SessionUserAggregate;
use netPhramework\authentication\SessionUserProvider;
use netPhramework\authentication\User;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\ReadableView;
use netPhramework\rendering\Viewable;

class Session
{
    private string $errorMessageKey = 'errorMessage';
    private string $errorCodeKey = 'errorCode';

	private SessionUserProvider $userProvider;
	private array $sessionVars;
	private ?SessionUser $user = null;

	public function __construct(?SessionUserProvider $userProvider = null)
	{
		$this->userProvider = $userProvider ?? new SessionUserAggregate();
	}

    /**
     * @param string $message
     * @return $this
     * @throws InvalidSession
     */
    public function addErrorMessage(string $message):Session
    {
        $this->start();
        $this->sessionVars[$this->errorMessageKey] = $message;
        return $this;
    }

    /**
     * @return string|null
     * @throws InvalidSession
     */
    public function getErrorMessageAndClear():?string
    {
        $this->start();
        if(isset($this->sessionVars[$this->errorMessageKey]))
        {
            $message = $this->sessionVars[$this->errorMessageKey];
            unset($this->sessionVars[$this->errorMessageKey]);
            return $message;
        }
        else return null;
    }

    /**
     * @param ResponseCode $code
     * @return $this
     * @throws InvalidSession
     */
    public function addErrorCode(ResponseCode $code):self
    {
        $this->start();
        $this->sessionVars[$this->errorCodeKey] = $code->value;
        return $this;
    }

    /**
     * @return ResponseCode
     * @throws InvalidSession
     */
    public function resolveResponseCode():ResponseCode
    {
        $this->start();
        if(isset($this->sessionVars[$this->errorCodeKey]))
        {
            $code = ResponseCode::tryFrom(
                $this->sessionVars[$this->errorCodeKey]) ?? ResponseCode::OK;
            unset($this->sessionVars[$this->errorCodeKey]);
            return $code;
        }
        else return ResponseCode::OK;
    }

    /**
     * Convenience method to generate a basic error Viewable and clear
     * the error data from the Session. Return null when no error exists.
     *
     * @return Viewable|null
     * @throws InvalidSession
     */
    public function getViewableError():?Viewable
    {
        if($errorMessage = $this->getErrorMessageAndClear())
        {
            return new ReadableView('error-message',['message'=>$errorMessage]);
        }
        else return null;
    }

	/**
	 * @param User $user
	 * @return $this
	 * @throws InvalidSession
	 */
	public function login(User $user):Session
	{
		$this->start();
		$this->user = $this->userProvider->fromUser($user);
		$this->user->populateVariables($this->sessionVars);
		return $this;
	}

	/**
	 * @return $this
	 * @throws InvalidSession
	 */
	public function logout():Session
	{
		$this->start();
        $this->user = null;
		$this->sessionVars = [];
		if (session_status() === PHP_SESSION_ACTIVE) {
			session_destroy();
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]);
			}
		}
		return $this;
	}

	/**
	 * @return bool
	 * @throws InvalidSession
	 */
	public function confirmLoggedIn():bool
	{
		$this->start();
		return $this->user !== null;
	}

	/**
	 * @return User|null
	 * @throws InvalidSession
	 */
	public function getUser(): ?User
	{
		$this->start();
		return $this->user;
	}

	/**
	 * @return void
	 * @throws InvalidSession
	 */
	private function start(): void
	{
		if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
			session_regenerate_id(true);
			$this->sessionVars =& $_SESSION;
			$this->user = $this->userProvider->fromArray($this->sessionVars);
		}
	}
}