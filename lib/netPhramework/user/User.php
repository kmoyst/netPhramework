<?php

namespace netPhramework\user;

interface User
{
	public function getUsername():string;
	public function getPassword():string;
	public UserRole $role {get;}
}