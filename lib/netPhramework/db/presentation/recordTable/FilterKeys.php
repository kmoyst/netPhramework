<?php

namespace netPhramework\db\presentation\recordTable;

enum FilterKeys:string
{
	case LIMIT = 'limit';
	case OFFSET = 'offset';
	case SORT_ARRAY = 'sort';
	case SORT_FIELD = 'sort-field';
	case SORT_DIRECTION = 'sort-direction';
}
