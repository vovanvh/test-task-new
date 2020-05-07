<?php

namespace http;

class XmlRequest extends Request
{

    protected function parseBody(string $body): array
    {
        $xml = simplexml_load_string($body);
        return [
            'firstName' => (string) $xml->firstName,
            'lastName' => (string) $xml->lastName,
            'age' => (string) $xml->age,
            'Salary' => (string) $xml->salary,
            'creditScore' => (string) $xml->creditScore,
        ];
    }
}
