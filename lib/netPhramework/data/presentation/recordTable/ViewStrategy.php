<?php

namespace netPhramework\data\presentation\recordTable;

interface ViewStrategy
{
	public function configureView(ViewContext $context):void;
}