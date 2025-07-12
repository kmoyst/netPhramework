<?php

namespace netPhramework\exchange;

class PassiveProcess extends RequestProcess
{
	public function prepare(RequestContext $context): self
	{
		$context->getApplication()->buildPassiveNode($this->node->root);
		return $this;
	}

}