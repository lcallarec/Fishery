<?php

require_once __DIR__.'/../vendor/autoload.php';

$rootPath = '../';

$fishery = new \Lc\Fishery\Application\FisheryHttp();
$fishery['debug'] = true;

(new \Lc\Fishery\FisheryConfigurator($fishery, $rootPath))->configure();

$fishery->get('/{dataProvider}/{schemaAlias}/{entity}/{id}', function(\Symfony\Component\HttpFoundation\Request $request) use($fishery) {

    return (new \Lc\Fishery\Http\ApiResponse($request, $fishery['entity.manager']))->create();
});

$fishery['schema.manager'];
$fishery->run();