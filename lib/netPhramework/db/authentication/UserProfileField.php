<?php

namespace netPhramework\db\authentication;

enum UserProfileField:string
{
	case EMAIL = 'email-address';
	case FIRST_NAME = 'first-name';
	case LAST_NAME = 'last-name';
}