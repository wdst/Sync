<?php

namespace Sync;

interface ISync{

    function requestHandler($key, $value);
    function responseHandler($key, $value);
    function syncHandler($handler);
    function go();
}