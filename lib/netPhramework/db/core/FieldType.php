<?php

namespace netPhramework\db\core;

enum FieldType:int
{
    case STRING = 1;
    case PARAGRAPH = 2;
    case INTEGER = 3;
    case FLOAT = 4;
    case BOOLEAN = 5;
    case DATE = 6;
    case TIME = 7;
}
