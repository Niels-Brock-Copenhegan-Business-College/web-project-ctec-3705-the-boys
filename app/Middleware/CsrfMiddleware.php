<?php
/**
 * app/Middleware/CsrfMiddleware.php
 */
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as SlimResponse;

class CsrfMiddleware implements MiddlewareInterface
{
    // Routes that skip CSRF (e.g. token-based withdrawals)
    private array $exempt = [
        '/interest/withdraw',
        '/my-interests/withdraw',
    ];

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if ($request->getMethod() === 'POST') {
            $uri  = $request->getUri()->getPath();

            // Skip exempt routes
            foreach ($this->exempt as $path) {
                if (str_starts_with($uri, $path)) {
                    return $handler->handle($request);
                }
            }

            $body  = $request->getParsedBody();
            $token = $body['csrf_token'] ?? '';

            if (!csrf_verify($token)) {
                $response = new SlimResponse();
                $response->getBody()->write(
                    '<h2 style="font-family:sans-serif;text-align:center;margin-top:4rem;">
                        403 — Invalid or expired form token.
                        <br><br>
                        <a href="javascript:history.back()">Go back</a>
                    </h2>'
                );
                return $response->withStatus(403);
            }
        }

        return $handler->handle($request);
    }
}