<?php

namespace netPhramework\exchange;

class ActiveProcess extends RequestProcess
{
	public function prepare(RequestContext $context):self
	{
		$this->location->getParameters()
			->clear()
			->merge($context->environment->postParameters);
		$context->getApplication()->buildActiveNode($this->node->root);
		return $this;
	}
}