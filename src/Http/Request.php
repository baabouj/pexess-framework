<?php

namespace Pexess\Http;

use Pexess\Pexess;
use Pexess\Validator\Validator;

class Request
{

    public function url(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return rtrim($path, '/') ?: '/';
    }

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function body(): array
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        return filter_var_array($body, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function query(): array
    {
        return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
    }

    public function validate(array $rules): bool|array
    {
        return Validator::validate($this->all(), $rules);
    }

    public function params(): array
    {
        return Pexess::$routeParams ?? [];
    }

    public function headers(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            $header = str_replace(' ', '_', str_replace('-', '_', strtolower(substr($key, 5))));
            $headers[$header] = $value;
        }
        return $headers;
    }

    public function cookies(): array
    {
        return $_COOKIE;
    }

    public function cookie(string $name)
    {
        return $_COOKIE[$name];
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function all(): array
    {
        return array_merge($this->body(),$this->files());
    }

    public function file(string $name)
    {
        return $_FILES[$name];
    }

}