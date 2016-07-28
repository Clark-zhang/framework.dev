<?php

namespace Simplex\Tests;

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    private function getFrameworkForException($exception)
    {
        $matcher = $this->createMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock('Symfony\Component\Routing\RequestContext')))
        ;
        $resolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $argumentResolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface');

        return new Framework($matcher, $resolver, $argumentResolver);
    }
}