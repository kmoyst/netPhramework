<?php

namespace netPhramework\db\authentication;

enum EnrolledUserField:string
{
	case USERNAME = 'username';
	case PASSWORD = 'password';
	case ROLE	  = 'role';
}