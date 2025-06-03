<?php

namespace netPhramework\db\presentation\recordTable;

interface ViewStrategy
{
	public function configureView(ViewContext $context):void;
}