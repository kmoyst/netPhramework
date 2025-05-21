<?php

namespace netPhramework\authentication;

interface SessionUser extends User
{
	public function populateVariables(array &$variables):void;
}