<?php

namespace Pexess\Exceptions;

use Pexess\Helpers\StatusCodes;

class NotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct("Not Found", StatusCodes::NOT_FOUND);
    }
}