<?php

namespace middleware;

/*
 * Not yet implemented, to be implemented in routers
 * before calling user_func();
 */
abstract class BaseMiddleware
{
    abstract public function executeMiddleware();

}