<?php 

namespace App\Middleware;

use \Adbar\Session;

class Authentication
{
	
    /**
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $this->session = new \Adbar\Session;
        $basePath = $request->getUri()->getBasePath();

        if(!empty($this->session->token)){
            $response = $next($request, $response);
            return $response;
        }else{
            $id = $this->session["id"];
            $this->session->clear();
            return $response->withStatus(200)->withHeader('Location', $basePath . '/auth/login');  
        }
    }
}