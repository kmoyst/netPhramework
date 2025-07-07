<?php

namespace netPhramework\db\authentication;

enum UserField:string
{
	case USERNAME 	= 'username';
	case PASSWORD 	= 'password';
	case ROLE	  	= 'role';
	case RESET_CODE = 'reset-code';
	case EMAIL 		= 'email-address';
	case FIRST_NAME = 'first-name';
	case LAST_NAME 	= 'last-name';
}