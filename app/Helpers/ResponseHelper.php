<?php

const SUCCESS = 1;
const ERROR = 0;
const NO_AUTH = 4;
const NO_PERMISSION = 5;
const NOT_EXITS=404;

const LAN = 'en';

function show($message = '', $status = ERROR, $data = [], $code = 200)
{
    return response(['message' => $message, 'status' => $status, 'data' => $data], $code);

}

function error($message)
{
    header('Content-Type:application/json');
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:*');
    header('Access-Control-Allow-Headers:*');
    echo json_encode(['status' => 0, 'message' => $message]);
    exit;
}

function showError($message, $code = 200)
{
    return response(['message' => $message, 'status' => ERROR, 'data' => []], $code);
}

function showSuccess($data)
{
    return response(['message' => 'ok', 'status' => SUCCESS, 'data' => $data], 200);
}

function showSuccessMessage($message, $data = [])
{
    return response(['message' => $message, 'status' => SUCCESS, 'data' => $data], 200);
}
