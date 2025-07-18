<?php

namespace netPhramework\db\user;

enum UserField:string
{
	case USERNAME 	= 'username';
	case PASSWORD 	= 'password';
	case ROLE	  	= 'role';
	case RESET_CODE = 'reset-code';
	case RESET_TIME = 'reset-code-time';
	case EMAIL 		= 'email-address';
	case FIRST_NAME = 'first-name';
	case LAST_NAME 	= 'last-name';
}