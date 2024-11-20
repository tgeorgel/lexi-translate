<?php

namespace Omaralalwi\LexiTranslate\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ApiLocalized
{
    /**
     * Handle an incoming API request and set the application locale.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \Closure $next The next middleware or handler in the pipeline.
     * @return \Symfony\Component\HttpFoundation\Response The processed HTTP response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocal = app()->getLocale();
        $headerFlag = Config::get('lexi-translate.api_locale_header_key');
        $locale = $request->header($headerFlag, $defaultLocal);

        if(!$locale) {
            $locale = app()->getLocale();
        }

        App::setLocale($locale);
        $request->merge(['locale' => $locale]);

        return $next($request);
    }
}
