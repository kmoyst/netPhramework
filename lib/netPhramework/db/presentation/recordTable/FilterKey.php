<?php

namespace netPhramework\db\presentation\recordTable;

enum FilterKey:string
{
	case LIMIT = 'limit';
	case OFFSET = 'offset';
	case SORT_ARRAY = 'sortArray';
	case SORT_FIELD = 'sortField';
	case SORT_DIRECTION = 'sortDirection';
}
