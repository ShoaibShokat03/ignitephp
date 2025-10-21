<?php

use App\Agent\Agent;
use App\Agent\AgentInitializer;
use App\Bot\Chat;
use App\Database\Db;
use App\Config\Gemini;
use App\Markdown\ResponseFormatter;
use App\Charts\ChartGenerator;
use App\Cache\QueryCache;
use Ignitephp\Core\Route;
use Ignitephp\Core\Request;


Route::get("/", function () {

    $data = Request::getJson();

    return [
        "Method" => Request::getMethod(),
        "Request" => Request::get("name"),
        "message" => "Hello, Welcome to ignitephp!",
    ];
});

Route::post("/login",function (){
    $data=Request::getJson();

    return [
        "Method"=>Request::getMethod(),
        "Request"=>Request::get("name"),
        "message"=>"Hello, Welcome to ignitephp!",
    ];
});
