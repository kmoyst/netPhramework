<?php

namespace netPhramework\authentication;

enum UserRole:int
{
	case VISITOR = 1;
	case STANDARD_USER = 10;
	case ADMIN = 20;
}
