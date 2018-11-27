<?php
namespace App\Controllers;
use Respect\Validation\Validator as v;
use Psr\Container\ContainerInterface;

class RepositoriesController{

  protected $ci;

  public function __construct(ContainerInterface $ci) {
    $this->ci = $ci;
  }

  function getValidation($keyword){
		return [
			'search keyword'=>v::notEmpty()
		];
	}

	public function __invoke($request, $response, $args) {
    $repository_name = $args['repository-name'];
    $get_data = $this->ci->ApiHandler->callApi('GET', "https://api.github.com/search/repositories?q={$repository_name}&sort=stars&order=desc", false);
    $data = json_decode($get_data, true);
    if($data['items']) {
      $filtereddata = $this->filterJSON($data['items']);
      $newResponse = $response->withJson($filtereddata);
      return $newResponse;
    }
	}

  // sanotizes to only the attributes that we need
  private function filterJSON($response_json){
    $sanitized_results = [];
    foreach((array) $response_json as $item){
      $sanitized_item = [
        'updated_at' => $item['updated_at'],
        'description' => $item['description'],
        'forks_count' => $item['forks_count'],
        'html_url' => $item['html_url'],
        'name' => $item['name'],
        'stargazers_count' => $item['stargazers_count'],
        'watchers_count' => $item['watchers_count'],
        'owner' => $item['owner']['login'],
        'avatar_url' => $item['owner']['avatar_url']
      ];
      array_push($sanitized_results, $sanitized_item);
    }
    return $sanitized_results;
  }
}
