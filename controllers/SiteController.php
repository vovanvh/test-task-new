<?php

namespace controllers;

use base\Controller;
use helpers\DateTime;
use http\JsonRequest;
use http\JsonResponse;
use http\Request;
use http\Response;
use http\XmlRequest;
use http\XmlResponse;

/**
 * Class SiteController
 * @package controllers
 */
class SiteController extends Controller
{

    use DateTime;

    /**
     * Default action
     */
    public function actionIndex()
    {
        $data = '';
        $defaultData = $this->config->getConfig('data');
        if ($this->getIsPostRequest()) {
            $data = [
                'firstName' => $this->getPostParam('first_name') ?? $defaultData['firstName'],
                'lastName' => $this->getPostParam('last_name') ?? $defaultData['lastName'],
                'dateOfBirth' => $this->getPostParam('dob') ?? $defaultData['dateOfBirth'],
                'Salary' => $this->getPostParam('salary') ?? $defaultData['Salary'],
                'creditScore' => $this->getPostParam('credit_score') ?? $defaultData['creditScore'],
            ];
            $format = $this->getPostParam('format', 'xml');
            $data = $this->sendRequest($format, $data);
        }
        $this->_render(
            'index',
            [
                'defaultValues' => $defaultData,
                'data' => $data,
            ]
        );
    }

    /**
     * @param string $format
     * @param array $data
     * @throws \Exception
     */
    protected function sendRequest(string $format, array $data)
    {
        $xml = $this->buildXml($data);
        $body = $this->getRequestBody($format, $data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->getConfig('url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders(strlen($body), $format));

        $data = curl_exec($ch);

        curl_close($ch);
        return $data;
    }

    /**
     * @param string $format
     * @param array $data
     * @return string|string[]
     * @throws \Exception
     */
    protected function getRequestBody(string $format, array $data)
    {
        if ($format == 'xml') {
            return $this->buildXml($data);
        } elseif ($format == 'json') {
            return $this->buildJson($data);
        }
    }

    /**
     * @param array $data
     * @return string|string[]
     * @throws \Exception
     */
    protected function buildXml(array $data)
    {
        $from = new \DateTime($data['dateOfBirth']);
        $to   = new \DateTime('today');
        $age = $from->diff($to)->y;
        $data['dateOfBirth'] = $age;
        return str_replace(
            [
                '{{firstName}}',
                '{{lastName}}',
                '{{dateOfBirth}}',
                '{{Salary}}',
                '{{creditScore}}',
            ],
            array_values($data),
            '<?xml version="1.0" ?>'
            . '<userInfo version="1.6">'
                . '<firstName>{{firstName}}</firstName>'
                . '<lastName>{{lastName}}</lastName>'
                . '<salary>{{Salary}}</salary>'
                . '<age>{{dateOfBirth}}</age>'
                . '<creditScore>{{creditScore}}</creditScore>'
            . '</userInfo>'
        );
    }

    protected function buildJson(array $data)
    {
        return json_encode(
            [
                'userInfo' => $data,
            ]
        );
    }

    /**
     * @param int $contentLength
     * @param string $format
     * @return string[]
     */
    protected function getHeaders(int $contentLength, string $format = 'xml')
    {
        $contentType = 'text/xml';
        if ($format == 'json') {
            $contentType = 'application/json';
        }
        return [
            "Content-type: " . $contentType,
            "Content-length: " . $contentLength,
            "Connection: close",
        ];
    }

    public function actionServer()
    {
        $postBody = file_get_contents("php://input");
        $request = $this->getRequestObject($postBody);
        $data = $request->getData();
        $code = 1;
        $codeDescription = 'success';
        $error = false;
        $transactionId = 'AC158457A86E711D0000016AB036886A03E7';
        if ($data['creditScore'] == 'bad') {
            $code = 0;
            $codeDescription = 'reject';
            $transactionId = false;
        }
        if ($data['creditScore'] == 'zero') {
            $code = 0;
            $codeDescription = 'error';
            $error = '';
            $transactionId = false;
        }

        $this->getResponseObject($code, $codeDescription, $error, $transactionId)->send();
        exit();
    }

    /**
     * @return bool
     */
    protected function getIsXmlRequest()
    {
        return $_SERVER["CONTENT_TYPE"] == 'text/xml' || empty($_SERVER["CONTENT_TYPE"]);
    }

    /**
     * @return bool
     */
    protected function getIsJsonRequest()
    {
        return $_SERVER["CONTENT_TYPE"] == 'application/json';
    }

    /**
     * @param string $postBody
     * @return Request
     */
    protected function getRequestObject(string $postBody): Request
    {
        if ($this->getIsJsonRequest()) {
            return new JsonRequest($postBody);
        }
        return new XmlRequest($postBody);
    }

    /**
     * @param int $code
     * @param string $codeDescription
     * @param string $error
     * @param string $transactionId
     * @return Response
     */
    protected function getResponseObject(int $code, string $codeDescription, string $error, string $transactionId): Response
    {
        if ($this->getIsJsonRequest()) {
            return new JsonResponse($code, $codeDescription, $error, $transactionId);
        }
        return new XmlResponse($code, $codeDescription, $error, $transactionId);
    }
}