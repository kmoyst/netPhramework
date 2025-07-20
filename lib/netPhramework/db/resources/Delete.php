<?php

namespace netPhramework\db\resources;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\nodes\AssetRecordProcess;
use netPhramework\exceptions\Exception;
use netPhramework\exchange\Exchange;
use netPhramework\exchange\ResponseCode;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToParent;

class Delete extends AssetRecordProcess
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