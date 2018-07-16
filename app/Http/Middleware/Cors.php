<?php

namespace App\Http\Middleware;

use Closure;
use Response;

class Cors
{
    /**
     * Массив доменов, с которых будем принимать запросы.
     *
     * @var array
     */
    protected $domains = [
        'http://crosscapital.link',
        'http://serverB.ru',
        'http://serverC.ru',
    ];

    /**
     * Метод, который обрабатывает все запросы, приходящие на сервер.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return Response
     */
    /*public function handle($request, Closure $next)
    {
        // проверим, присутствует ли заголовок HTTP_ORIGIN в запросе
        // и разрешен ли домен
        $origin = $request->headers->get('Origin');

        //если есть, то устанавливаем нужные заголовки
        return $next($request)
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header(
                'Access-Control-Allow-Headers',
                'Authorization, Origin, X-Requested-With, Accept, X-PINGOTHER, Content-Type'
            );
    }*/

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        $response->header('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
