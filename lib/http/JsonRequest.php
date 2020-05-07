<?php

namespace http;

class JsonRequest extends Request
{

    protected function parseBody(string $body): array
    {
        $array = json_decode($body, true);
        return $array['userInfo'];
    }
}
