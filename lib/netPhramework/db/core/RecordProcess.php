<?php

namespace netPhramework\db\core;

use netPhramework\core\Leaf;

abstract class RecordProcess extends Leaf implements AssetNode
{
	use RecordChild;
}