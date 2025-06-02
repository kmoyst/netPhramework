<?php

namespace netPhramework\db\presentation\recordTable\query;

enum Key:string
{
	case LIMIT = 'limit';
	case OFFSET = 'offset';
	case SORT_ARRAY = 'sortArray';
	case SORT_FIELD = 'sortField';
	case SORT_DIRECTION = 'sortDirection';
	case CONDITION_SET = 'conditionSet';
	case CONDITION_FIELD = 'conditionField';
	case CONDITION_OPERATOR = 'conditionOperator';
	case CONDITION_VALUE = 'conditionValue';
	case CONDITION_GLUE = 'conditionGlue';
}
