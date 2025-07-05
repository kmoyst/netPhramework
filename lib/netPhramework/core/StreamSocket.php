<?php

namespace netPhramework\core;

use netPhramework\exceptions\StreamSocketException;

class StreamSocket
{
	/**
	 * @var resource
	 */
	private $socket;
	private string $writeErrorMessage;

	public function __construct(
		private readonly string $address,
		private readonly int $timeout = 30,
		private readonly string $eol = "\r\n",
		private readonly int $maxLineLength = 8192
	) {}

	public function __destruct()
	{
		$this->close();
	}

	/**
	 * @return $this
	 * @throws StreamSocketException
	 */
	public function open():self
	{
		// @ prefix suppresses PHP warning error
		$socket = @stream_socket_client(
			$this->address,
			$errorCode,
			$errorMessage,
			$this->timeout)
		;
		if(!$socket) throw new StreamSocketException($errorMessage);
		$this->socket = $socket;
		return $this;
	}

	public function close():self
	{
		fclose($this->socket);
		return $this;
	}

	public function write(string $line):self
	{
		fwrite($this->socket, $line . $this->eol);
		return $this;
	}

	public function confirm():bool
	{
		$msg = fgets($this->socket, $this->maxLineLength);
		if(preg_match('/^(2|3)/', $msg)) return true;
		else
		{
			$this->writeErrorMessage = $msg === false ? '' : trim($msg);
			return false;
		}
	}
}