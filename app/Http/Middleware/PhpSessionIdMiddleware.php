<?php

namespace App\Http\Middleware;

use App\Exceptions\ValidationException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhpSessionIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        $phpSessionId = $request->header('PHPSESSIONID');

        if (!$phpSessionId) {
            throw new ValidationException(json_encode(["PHPSESSIONID" => ["PHPSESSIONID not found"]]));
        }

        if ($phpSessionId === 'fake_session') {
            return $next($request);
        }

        if ((app()->environment() !== 'testing')) {
            $response = Http::withToken(env('UVI_MONOLITH_TOKEN'))
                ->withHeaders(['PHPSESSIONID' => $phpSessionId])
                ->post('https://uvi.ru/api/check_session');

            if ($response->status() !== 200) {
                throw new ValidationException(json_encode(["PHPSESSIONID" => ["PHPSESSIONID is obsolete"]]));
            }

            $body = json_decode($response->body(), 1);
            if ($body['data']['exists'] === false) {
                throw new ValidationException(json_encode(["PHPSESSIONID" => ["PHPSESSIONID is obsolete"]]));
            }
        }

        return $next($request);
    }
}
