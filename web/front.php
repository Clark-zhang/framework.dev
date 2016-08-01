<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpFoundation;
use Symfony\Component\EventDispatcher\EventDispatcher;


$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new Simplex\StringResponseListener());

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$framework = new Simplex\Framework($dispatcher, $controllerResolver,$requestStack , $argumentResolver);
$response = $framework->handle($request);

$response->send();