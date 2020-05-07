<?php

namespace http;

class XmlResponse extends Response
{
    protected function getHeaders(): array
    {
        return [
            "Content-type: text/xml"
        ];
    }

    protected function getBody(): string
    {
        $search = [
            '{{returnCode}}',
            '{{returnCodeDescription}}',
        ];
        $replace = [
            $this->code,
            $this->codeDescription,
        ];
        if ($this->codeDescription == 'success') {
            $search[] = '{{transactionId}}';
            $replace[] = $this->transactionId;
        }
        if ($this->codeDescription == 'error') {
            $search[] = '{{returnError}}';
            $replace[] = $this->error;
        }
        return str_replace(
            $search,
            $replace,
            '<?xml version="1.0" ?>'
            . '<userInfo version="1.6">'
            . '<returnCode>{{returnCode}}</returnCode>'
            . '<returnCodeDescription>{{returnCodeDescription}}</returnCodeDescription>'
            . ($this->codeDescription == 'success' ? '<transactionId>{{transactionId}}</transactionId>' : '')
            . ($this->codeDescription == 'error' ? '<returnError>{{returnError}}</returnError>' : '')
            . '</userInfo>'
        );
    }
}
