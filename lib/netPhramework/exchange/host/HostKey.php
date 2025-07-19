<?php

namespace netPhramework\exchange\host;

enum HostKey:string
{
	case HOST_MODE   		 = 'MODE';
	case HOST_PROTOCOL  	 = 'PROTOCOL';
	case HOST_DOMAIN 		 = 'DOMAIN';
	case SMTP_SERVER_ADDRESS = 'SMTP_SERVER_ADDRESS';
	case SMTP_SERVER_NAME 	 = 'SMTP_SERVER_NAME';
}
