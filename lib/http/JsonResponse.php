<?php

namespace http;

class JsonResponse extends Response
{
    protected function getHeaders(): array
    {
        return [
            "Content-type: application/json"
        ];
    }

    protected function getBody(): string
    {
        $response = [
            'SubmitDataResult' => $this->codeDescription
        ];
        if ($this->codeDescription == 'error') {
            $response['SubmitDataErrorMessage'] = '';
        }
        return json_encode($response);
    }
}
