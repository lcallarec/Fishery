<?php

namespace Lc\Fishery\Http;

use Lc\Fishery\Schema\EntityManager;
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

    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em      = $em;
    }

    public function create()
    {
        ///{dataProvider}/{schemaAlias}/{entity}/{id}
        return new JsonResponse($this->em->get($this->request->get('schemaAlias'), $this->request->get('entity'), [$this->request->get('id')]));
    }
}
