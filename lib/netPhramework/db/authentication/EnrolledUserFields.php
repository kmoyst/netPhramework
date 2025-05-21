<?php

namespace netPhramework\db\authentication;

enum EnrolledUserFields:string
{
	case USERNAME = 'username';
	case PASSWORD = 'password';
}