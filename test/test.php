<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Sync\Sync as Sync;
use Sync\GetterUnit as GetterUnit;
use Sync\SenderUnit as SenderUnit;

Class DB {

    public $result;

    function data()
    {
        return [
            "one", "two", "three"
        ];
    }

    function status($key, $value)
    {
        $this->result[$key] = $value;
    }
}

Class Api{

    function get($val, $key){
        return $key . " = " . $val . " ... Done";
    }
}

$DB = new DB();
$API = new Api();

$a = new GetterUnit();

$a->data = $DB->data();

$b = new SenderUnit();

$b->requestHandler(function($key, $value) use ($API) {
    return $API->get($value, $key);
});

$a->responseHandler(function($key, $value) use ($DB) {
    return $DB->status($key, $value);
});

$sync = new Sync($a, $b);

// TEST 1

$sync->go();

print_r($DB->result);

// TEST 2

$sync->syncHandler(
    function ($data, $sync){
        foreach($data as $key => $value){
            $result = $sync->requestHandler($key, $value);
            $sync->responseHandler($key, $result . " - test");
        }
    }
);

$sync->go();

print_r($DB->result);