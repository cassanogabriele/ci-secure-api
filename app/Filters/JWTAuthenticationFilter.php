<?php

namespace App\Filters;

use App\Dto\Response\HttpResponse;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class JWTAuthenticationFilter implements FilterInterface
{
    private const error_message = 'Unauthorized';
    public function before(RequestInterface $request, $droits_requis = null, $services_requis = null)
    {
        $errorResponse = new HttpResponse(ResponseInterface::HTTP_FORBIDDEN,null,[self::error_message]);

        //Return 401 if no token is provided
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        if(!$authHeader) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)->setJSON($errorResponse);
        }
        
        //Get the token from the header
        $key        = getenv('JWT_SECRET_KEY');
		$arr        = explode(' ', $authHeader);
		$token      = $arr[1];
        
        //Decode the token
        try{
            JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)->setJSON($errorResponse);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}