<?php

namespace App\Middleware;

use App\Middleware\Concerns\Response as ConcernsResponse;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AdminAuthenticate implements Middleware
{
    use ConcernsResponse;

    public function handle(Request $request): void
    {
        if (!Auth::isAuthenticateAsAdmin()) {
            $this->sendUnauthorizedResponse('Não autorizado');
        }
    }
}
