<?php

namespace netPhramework\networking;

class StreamSocket
{
	/**
	 * @var resource
	 */
	private $socket;
	private string $lastMessage = '';

	public function __construct(
		private readonly string $address,
		private readonly int $timeout = 30,
		private readonly string $eol = "\r\n",
		private readonly int $maxLineLength = 8192
	) {}

	public function __destruct()
	{
		if(is_resource($this->socket)) $this->close();
	}

	/**
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

	public function confirm(?int $specificCode = null):bool
	{
		$msg = fgets($this->socket, $this->maxLineLength);
		if(preg_match("/^(" . $specificCode ?? '2|3' . ")/", $msg)) return true;
		else
		{
			$this->lastMessage = $msg === false ? 'fgets fail' : trim($msg);
			return false;
		}
	}

	public function readResponseCode():?int
	{
		$msg = fgets($this->socket, $this->maxLineLength);
		if(preg_match('/^(\d{3})/', (string)$msg, $matches))
			return (int)$matches[1];
		else
		{
			$this->lastMessage = 'fgets fail';
			return null;
		}
	}

	public function getLastMessage(): string
	{
		return $this->lastMessage;
	}
}