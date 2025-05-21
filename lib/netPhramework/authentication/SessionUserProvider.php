<?php

namespace netPhramework\authentication;

interface SessionUserProvider
{
	public function fromUser(User $user):?SessionUser;
	public function fromVariables(array $vars):?SessionUser;
}