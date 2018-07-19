<?php 

namespace App\Middleware;

use \Adbar\Session;

class Safety
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
        $this->session  = new \Adbar\Session;
        $basePath       = $request->getUri()->getBasePath();
        $route          = $request->getAttribute('route');
        $name           = $route->getName();

        switch (count($this->session->token)) {
            case 1:
                $permissions = $this->session->permissions; 

                    foreach ($permissions as $i => $perms) {
                        if ($perms->name == $name) {
                            $busca = $perms;
                            break;
                        }
                    }
                      
                    if (isset($busca)) {
                        $response = $next($request, $response);
                        return $response;
                    } else {
                        return $response->withHeader('Location', $basePath . '/auth/error');    
                    }

                break;
            
            default:
                $id = $this->session["id"];
                $this->session->clear();
                return $response->withStatus(200)->withHeader('Location', $basePath . '/auth/login');  
                break;
        }
    }
}