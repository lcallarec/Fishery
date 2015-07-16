<?php

require_once __DIR__.'/../vendor/autoload.php';

$rootPath = '../';

$Fishery = new \Lc\Fishery\Application\FisheryHttp();
$Fishery['debug'] = true;

(new \Lc\Fishery\FisheryConfigurator($Fishery, $rootPath))->configure();


$Fishery->get('/{dataProvider}/{schemaAlias}/{entity}/{id}', function(\Symfony\Component\HttpFoundation\Request $request) use($Fishery) {

    return (new \Lc\Fishery\Http\ApiResponse($request, $Fishery['entity.manager']))->create();
});

$Fishery->run();