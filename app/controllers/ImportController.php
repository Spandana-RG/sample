<?php
namespace App\Controllers;
use Psr\Container\ContainerInterface;

class ImportController{

  protected $ci;

  public function __construct(ContainerInterface $ci) {
    $this->ci = $ci;
  }

  public function __invoke($request, $response, $args) {
    error_log("in this as pe");
    $post_data = $request->getParsedBody();
    echo $post_data;
    var_dump($post_data);
    $data = $this->ci->APIHandler->getPackageJSON($post_data['html_url']);
    $newResponse = $response->withJson($data);
    echo $newResponse;
    error_log($newResponse);
    return $newResponse;
  }

}
