<?php

namespace netPhramework\authentication;

interface User
{
	public function getUsername():string;
	public function getPassword():string;
}