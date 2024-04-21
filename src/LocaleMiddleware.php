<?php

namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Symfony\Component\Translation\Translator;

class LocaleMiddleware
{
    private $translator;
    private $allowedLocales;
    private $defaultLocale;

    public function __construct(Translator $translator, array $allowedLocales, string $defaultLocale)
    {
        $this->translator = $translator;
        $this->allowedLocales = $allowedLocales;
        $this->defaultLocale = $defaultLocale;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $queryParams = $request->getQueryParams();
        $locale = $queryParams['lang'] ?? $this->defaultLocale;

        if (!in_array($locale, $this->allowedLocales)) {
            $locale = $this->defaultLocale;
        }

        $this->translator->setLocale($locale);

        return $handler->handle($request);
    }
}
