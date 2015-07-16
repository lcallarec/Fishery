<?php

namespace Lc\Fishery\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class Container
 *
 * @package Lc\Fishery
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class ApiResponse
{

    public function __construct(Request $request, $em)
    {
        $this->request = $request;
        $this->em = $em;
    }

    public function create()
    {
        return new JsonResponse($this->em->get(1, 2, 3));
    }
}
