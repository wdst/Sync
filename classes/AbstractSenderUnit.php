<?php

namespace Sync;

abstract class AbstractSenderUnit{

    public $requestHandler;

    function requestHandler($handler)
    {
        $this->requestHandler = $handler;
    }

}