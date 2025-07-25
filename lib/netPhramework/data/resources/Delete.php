<?php

namespace netPhramework\data\resources;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\nodes\RecordProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;

class Delete extends RecordProcess
{
    protected Redirector $dispatcher;

	public function __construct(
		?Redirector $dispatcher = null,
		)
	{
        $this->dispatcher = $dispatcher ?? new RedirectToParent('');
	}

    /**
     * @param Exchange $exchange
     * @return void
     * @throws MappingException
     * @throws Exception
     */
	public function handleExchange(Exchange $exchange): void
	{
		$result = $this->record->drop();
		if($result)
		{
			$exchange->session->setFeedbackCode(ResponseCode::OK);
			$exchange->session->addFeedbackMessage("Record deleted");
		}
		else
		{
			$exchange->session
				->setFeedbackCode(ResponseCode::FAILED_DEPENDENCY)
				->addFeedbackMessage("Unable to delete record")
			;
		}
		$exchange->redirect($this->dispatcher);
	}
}