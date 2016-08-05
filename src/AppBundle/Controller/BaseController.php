<?php

namespace AppBundle\Controller;

use AppBundle\Helper\Errors;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    protected function dieOnError($errorCode)
    {
        return new JsonResponse([
            'success' => false,
            'error' => $errorCode
        ]);
    }

    protected function liveOnSuccess_export($pswd)
    {
        return new JsonResponse([
            'success' => true,
            'error' => Errors::Success,
            'password' => $pswd
        ]);
    }

    protected function liveOnSuccess_import($jsonObj)
    {
        return new JsonResponse([
            'success' => true,
            'error' => Errors::Success,
            'data' => ($jsonObj)
        ]);
    }
}
