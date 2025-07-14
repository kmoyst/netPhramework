<?php

namespace netPhramework\user\resources;

use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\nodes\Resource;
use netPhramework\routing\redirectors\RedirectToRoot;

class LogOut extends Resource
{
	private Redirector $afterLogout;

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->session->logout();
		$exchange->redirect($this->redirector ?? new RedirectToRoot());
	}

	public function setAfterLogout(Redirector $afterLogout): self
	{
		$this->afterLogout = $afterLogout;
		return $this;
	}
}