<?php

namespace App\Html;

use App\Repositories\CountyRepository;
use App\Repositories\CityRepository;

class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    private static function postRequest()
    {
        $resource = self::getResourceName();
        switch ($resource) {
            case 'counties':
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $repository = new CountyRepository();
                    $newId = $repository->create($data);
                    $code = 201;
                }
                Response::response(['id' => $newId], $code);
                break;

            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function getRequest()
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $repository = new CountyRepository();
                $entities = $repository->getAll();                
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, 200);
                break;
                
            case 'city':
                $countyId = $_GET['county_id'] ?? null;
                $letter = $_GET['letter'] ?? null;

                if ($countyId) {
                    $repository = new CityRepository();
                    $cities = $repository->getCitiesByCountyAndLetter($countyId, $letter);
                    $code = $cities ? 200 : 404;
                    Response::response($cities, $code);
                } else {
                    Response::response([], 404, "County not found");
                }
                break;

            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function deleteRequest()
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
            return;
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $repository = new CountyRepository();
                $result = $repository->delete($id);
                $code = $result ? 204 : 404;
                Response::response([], $code);
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    private static function getRequestData(): ?array
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    private static function putRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $putRequestData = self::getRequestData();
        $resource = self::getResourceName();
        switch ($resource) {
            case 'counties':
                $id = self::getResourceId();
                $repository = new CountyRepository();
                $entity = $repository->find($id);
                $code = $entity ? 202 : 404;
                if ($entity) {
                    $result = $repository->update($id, ['name' => $putRequestData['name']]);
                    $code = $result ? 202 : 404;
                }
                Response::response([], $code);
                break;
            default:
                Response::response([], 404, "$uri not found");
        }
    }

    private static function getArrUri(string $requestUri): ?array
    {
        return explode("/", $requestUri) ?? null;
    }

    private static function getResourceName(): string
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = $arrUri[count($arrUri) - 1];
        if (is_numeric($result)) {
            $result = $arrUri[count($arrUri) - 2];
        }

        return $result;
    }

    private static function getResourceId(): int
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = 0;
        if (is_numeric($arrUri[count($arrUri) - 1])) {
            $result = $arrUri[count($arrUri) - 1];
        }

        return $result;
    }
}
