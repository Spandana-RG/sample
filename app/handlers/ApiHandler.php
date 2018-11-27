<?php
namespace App\Handlers;
use Psr\Container\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;



class ApiHandler {
  protected $ci;

  public function __construct(ContainerInterface $ci) {
    $this->ci = $ci;
  }

  public function callApi($method, $url, $data) {
    $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: 111111111111111111111',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
  }


  // check if there is a package.json and fetch it
    public function getPackageJSON(string $url) {
        $content_url = 'https://api.github.com/repos/'.str_replace('https://github.com/', '', $url).'/contents/package.json';

        $client = new Client();

        // check for package.json
        try{
          $res = $client->get($content_url);
          $json_response = json_decode($res->getBody());
          if (isset($json_response->message)){
            return ['error' => 'There is no package.json file in the project.'];
          }else{
            $package_json_url = $json_response->download_url;
          }
        }catch (ClientException $e){
          return ['error' => 'There is an error connecting to Github.'];
        }

        // get package.json
        try{
          $res = $client->get($package_json_url);
        }catch (ClientException $e){
          return ['error' => 'There is an error connecting to Github.'];
        }

        // parse package.json
        if ($res->getStatusCode() == 200){
          $response_json = json_decode($res->getBody());
          $repository = $this->parseJSON($response_json);
          $repository['url'] = $url;
          return $repository;
        }else{
          return ['error' => 'There is an issue accessing Github.'];
        }
    }

    //Parse the JSON response to get dependencies and devDependencies
    private function parseJSON($response_json){
      if(!isset($response_json->devDependencies) && !isset($response_json->dependencies)){
        return array('error' => 'The package.json has no dependencies.');
      }
      $repository = ['name' => $response_json->name];
      $dependencies = [];
      if(isset($response_json->devDependencies)){
        foreach($response_json->devDependencies as $dep => $versions){
          array_push($dependencies, $dep);
        }
      }
      if(isset($response_json->dependencies)) {
        foreach($response_json->dependencies as $dep => $versions){
          array_push($dependencies, $dep);
        }
      }
      $repository['dependencies'] = $dependencies;
      return $repository;
    }
}
