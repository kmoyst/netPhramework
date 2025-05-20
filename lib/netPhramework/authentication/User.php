<?php

namespace netPhramework\authentication;

interface User
{
	public const string USERNAME_KEY = 'username';
	public const string PASSWORD_KEY = 'password';

	public function getUsername():string;
	public function getPassword():string;
}