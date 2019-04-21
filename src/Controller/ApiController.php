<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends Controller {
    /** 
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Get the value of statusCode
     * 
     * @return integer
     */
    public function getStausCode(){
        return $this->statusCode;
    }

   /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode){
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Return a JSON response
     * 
     * @param array $data
     * @param array $headers
     * 
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendResponse($data, $headers = []){
        return new JsonResponse($data, $this->getStausCode(), $headers);
    }

    /**
     * Set an error message and return JSON response
     * 
     * @param string errors
     * 
     * @return Symfony\Component\Httpoundation\JsonResponse
     */
    public function sendErrorResponse($errors, $headers=[]){
        $data = [
            'errors' => $errors
        ];
        return new JsonResponse($data, $this->getStausCode(), $headers);
    }

    /**
     * Returns 401 unautherized error response
     * 
     * @param string $message
     * 
     * @return Symfony\Component\Httpoundation\JsonResponse
     */
    public function sendUnauthorizedResponse($message = 'Not Authorized'){
        return $this->setStatusCode(401)->sendErrorResponse($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     * @param string $message
     * 
     * returns Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendValidationError($message = 'Validation errors'){
        return $this->setStatusCode(422)->sendErrorResponse($message);
    }

    /**
     * Returns 404 error
     * 
     * @param string $message
     * 
     * returns Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendNotFoundError($message='Not Found'){
        return $this->setStatusCode(404)->sendErrorResponse($message);
    }

    /**
     * Return 201 message
     * 
     * @param array $data
     * 
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendSuccessMessage($data=[], $code=200){
        return $this->setStatusCode($code)->sendResponse($data);
    }

    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request){
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
    
        if ($data === null) {
            return $request;
        }
    
        $request->request->replace($data);
    
        return $request;
    }
    /**
     * Attempt authorization using jwt-verifier
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if (! isset( $_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }
    
        $authType = null;
        $authData = null;
    
        // Extract the auth type and the data from the Authorization header.
        @list($authType, $authData) = explode(" ", $_SERVER['HTTP_AUTHORIZATION'], 2);
    
        // If the Authorization Header is not a bearer type, return a 401.
        if ($authType != 'Bearer') {
            return false;
        }
    
        // Attempt authorization with the provided token
        try {
    
            // Setup the JWT Verifier
            $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
                            ->setAdaptor(new \Okta\JwtVerifier\Adaptors\SpomkyLabsJose())
                            ->setAudience('api://default')
                            ->setClientId('{0oahpkb0l65shwFPf356}')
                            ->setIssuer('https://dev-350939.okta.com/oauth2/default')
                            ->build();
    
            // Verify the JWT from the Authorization Header.
            print_r(jwtVerifier);
            $jwt = $jwtVerifier->verify($authData);
        } catch (\Exception $e) {
    
            // We encountered an error, return a 401.
            return false;
        }
    
        return true;
    }
}