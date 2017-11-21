<?php

namespace Isholao\Middleware\Tests;

use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{

    public function testShortcut()
    {
        $manager = new \Isholao\Middleware\Manager(function($req, $res)
        {
            return $req . ' ' . $res;
        });

        $manager->register(function($req, $res, $next)
        {
           return $next($req, $res);
        });

        $manager->register(function($req, $res, $next)
        {
            return $next($req, $res);
        });

        $this->assertSame('arg1 arg2', $manager->call('arg1', 'arg2'));
    }

}
