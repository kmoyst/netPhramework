<?php

namespace netPhramework\rendering;

interface Viewable
{
	public function getTemplateName():string;
	public function getVariables():iterable;
}