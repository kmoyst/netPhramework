<?php

namespace netPhramework\core;

enum RuntimeKey:string
{
	case HOST_MODE   		 = 'MODE';
	case SMTP_SERVER_ADDRESS = 'SMTP_SERVER_ADDRESS';
	case SMTP_SERVER_NAME 	 = 'SMTP_SERVER_NAME';
	case DOMAIN				 = 'HOST';
	case DOCUMENT_ROOT		 = 'DOCUMENT_ROOT';
}
