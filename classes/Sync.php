<?php

namespace Sync;

use \Sync\ISync as ISync;
use \Sync\AbstractGetterUnit as AbstractGetterUnit;
use \Sync\AbstractSenderUnit as AbstractSenderUnit;

Class Sync implements ISync{

    public $data, $result;
    public $GetterUnit, $SenderUnit;
    public $handler;

    public function __construct(AbstractGetterUnit $a, AbstractSenderUnit $b)
    {
        $this->GetterUnit = $a;
        $this->SenderUnit = $b;

        $this->data = $this->GetterUnit->data;

        if(empty($this->data)){
            die ("Not data");
        }
    }

    function go()
    {
        if(!is_callable($this->handler) || empty($this->handler)){
            $this->handler = array($this, 'defaultHandler');
        }

        return call_user_func($this->handler, $this->data, $this);
    }
    
    function syncHandler($handler)
    {
        $this->handler = $handler;
    }

    function defaultHandler($data, $t)
    {
        foreach($data as $key => $value){
            $result = $this->requestHandler($key, $value);
            $this->responseHandler($key, $result);
        }
    }

    function requestHandler($key, $value)
    {
        if(is_callable($this->SenderUnit->requestHandler)){
            return call_user_func($this->SenderUnit->requestHandler, $key, $value);
        } elseif (is_array($this->SenderUnit->requestHandler)) {
            return $this->SenderUnit->requestHandler[$key];
        }
        return null;
    }

    function responseHandler($key, $value)
    {
        if(is_callable($this->GetterUnit->responseHandler)){
            return call_user_func($this->GetterUnit->responseHandler, $key, $value);
        } elseif (is_array($this->GetterUnit->responseHandler)) {
            return $this->GetterUnit->responseHandler[$key] = $value;
        }
        return null;
    }
}