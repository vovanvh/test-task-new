<?php

namespace http;

abstract class Response
{

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $codeDescription;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * Response constructor.
     * @param int $code
     * @param string $codeDescription
     * @param string $error
     * @param string $transactionId
     */
    public function __construct(int $code, string $codeDescription, string $error, string $transactionId)
    {
        $this->code = $code;
        $this->codeDescription = $codeDescription;
        $this->error = $error;
        $this->transactionId = $transactionId;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendResponse();
    }

    protected function sendResponse()
    {
        echo $this->getBody();
    }

    protected function sendHeaders()
    {
        foreach ($this->getHeaders() as $header) {
            header($header);
        }
    }

    /**
     * @return array
     */
    abstract protected function getHeaders(): array;

    /**
     * @return string
     */
    abstract protected function getBody(): string;
}
