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

    /**
     * @api {get} /counties Get list of counties
     * @apiName index
     * @apiGroup Counties
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} counties       List of counties.
     * @apiSuccess {Number}   counties.id    County id.
     * @apiSuccess {String}   counties.name  County Name.
     *
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data":[
     *              {"id":2,"name":"B\u00e1cs-Kiskun"},
     *              {"id":3,"name":"Baranya"},
     *              ...
     *          ],
     *          "message":"OK",
     *          "status":200
     *      }
     * @apiError NotFound 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *          "data":[],
     *          "message":"Not Found",
     *          "status":404
     *      }
     */
    
    private static function getRequest()
    {
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $repository = new CountyRepository();
                $resourceId = self::getResourceId();
                $code = 200;
                if ($resourceId) {
                    $entity = $repository->find($resourceId);
                    Response::response($entity, $code);
                    break;
                }

                $entities = $repository->getAll();                
                if (empty($entities)) {
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
                
            case 'filters':
                $filterData = self::getFilterData();
                $data = self::getRequestData();
                if (isset($data['needle'])) {
                    $needle = $data['needle'];
                    $repository = new CountyRepository();
                    $entities = $repository->findByName($needle);
                    $code = 200;
                    if (empty($entities)) {
                        $code = 404;
                    }
                    Response::response($entities, $code);
                }
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    /**
     * @api {get} /counties/:id Get single county by :id
     * @apiParam {Number} id County unique ID.
     * @apiName getCounty
     * @apiGroup Counties
     * @apiVersion 1.0.0 
     * 
     * @apiSuccess {Object[]} counties       List of counties.
     * @apiSuccess {Number}   counties.id    County id.
     * @apiSuccess {String}   counties.name  County Name.
     *
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *          "data":[
     *              {"id":3,"name":"Baranya"}
     *          ],
     *          "message":"OK",
     *          "status":200
     *      }
     * @apiError NotFound 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *          "data":[],
     *          "message":"Not Found",
     *          "status":404
     *      }
     */
    function getCounty($id)
    {
        return true;
    }
    /**
     * @api {delete} /counties/:id Delete county with :id
     * @apiParam {Number} id County unique ID.
     * @apiName delete
     * @apiGroup Counties
     * @apiVersion 1.0.0
     *
     * @apiSuccessExample {json} Success-Response:
     *      HTTP/1.1 204 No content
     *      {
     *          "data":[],
     *          "message":"No content",
     *          "status":204
     *      }
     * 
     * @apiError NotFound 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *          "data":[],
     *          "message":"Not Found",
     *          "status":404
     *      } 
     */
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
                $code = 404;
                $repository = new CountyRepository();
                $result = $repository->delete($id);
                if ($result) {
                    $code = 204;
                }
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
                $id = self::getResourceId(); // $putRequestData['id'];
                $repository = new CountyRepository();
                $entity = $repository->find($id);
                $code = 404;
                if ($entity) {
                    $result = $repository->update($id, ['name' => $putRequestData['name']]);
                    if ($result) {
                        $code = 202;
                    }
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

    private static function getFilterData(): array
    {
        $result = [];
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);

        return $result;
    }
}