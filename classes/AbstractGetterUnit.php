<?php

namespace Sync;

abstract class AbstractGetterUnit{

    public $data, $responseHandler;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function responseHandler($handler)
    {
        $this->responseHandler = $handler;
    }
}