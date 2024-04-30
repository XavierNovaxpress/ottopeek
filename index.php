<?php

require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Parsedown;
use Dotenv\Dotenv;
use Symfony\Component\Yaml\Yaml;

// Initialisation des services
$dotenv = loadEnvironment();
$translator = setupTranslator();
$twig = setupTwig($translator);
$app = setupSlim($twig);

// Middleware pour la gestion de la locale
$app->add(function ($request, $handler) use ($translator, $twig) {
    return localeMiddleware($request, $handler, $translator, $twig);
});

// Routage
setupRoutes($app, $twig, $translator);

// Exécution de l'application
$app->run();

// Functions
function loadEnvironment() {
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }
}

function setupTranslator() {
    $defaultLocale = $_ENV['LOCALE'] ?? 'fr';
    $translator = new Translator($defaultLocale);
    $translator->addLoader('yaml', new YamlFileLoader());

    $locales = ['fr', 'en', 'it', 'es', 'de'];
    foreach ($locales as $locale) {
        $translator->addResource('yaml', __DIR__ . "/translations/messages.$locale.yaml", $locale);
    }

    return $translator;
}

function setupTwig($translator) {
    $twig = Twig::create(__DIR__ . '/templates');
    $twig->getEnvironment()->addExtension(new TranslationExtension($translator));
    return $twig;
}

function setupSlim($twig) {
    $app = AppFactory::create();
    $app->add(TwigMiddleware::create($app, $twig));
    return $app;
}

function localeMiddleware($request, $handler, $translator, $twig) {
    $queryParams = $request->getQueryParams();
    $locale = $queryParams['lang'] ?? $_ENV['LOCALE'] ?? 'fr';
    $allowedLocales = ['fr', 'en', 'it', 'es', 'de'];

    if (!in_array($locale, $allowedLocales)) {
        $locale = $_ENV['LOCALE'] ?? 'fr';
    }

    $translator->setLocale($locale);
    $twig->getEnvironment()->addGlobal('locale', $locale);

    return $handler->handle($request);
}

function setupRoutes($app, $twig, $translator) {
      // Redirection de la racine vers la page d'accueil
    $app->get('/', function ($request, $response, $args) use ($app) {
        return $response->withHeader('Location', '/home')->withStatus(302);
    });

    // Route pour les autres pages
    $app->get('/{page}', function ($request, $response, $args) use ($twig, $translator) {
        $queryParams = $request->getQueryParams();
        $channel = $queryParams['channel'] ?? 'default';
        $page = $args['page'] ?? 'home';

        $globalData = getGlobalData($translator, $channel, $page);
        return $twig->render($response, "_{$page}.twig", $globalData);
    });
}

function getGlobalData($translator, $channel, $page) {

    $parsedown = new Parsedown();
    $currentLocale = substr($translator->getLocale(), 0, 2);

    // Détermination des chemins des fichiers Markdown
    $cgvPath = __DIR__ . "/translations/markdown/cgv-{$currentLocale}.md";
    $privacyPath = __DIR__ . "/translations/markdown/privacy-{$currentLocale}.md";

    // Définition des variables globales
    // Récupération du nom de domaine
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $webrootURL = $protocol.$_SERVER['HTTP_HOST'];

    $companyName = $_ENV['COMPANY_NAME'] ?? 'Ottopeek';
    $contactEmail = $_ENV['CONTACT_EMAIL'] ?? 'contact@ottopeek.com';
    $companyAdress = $_ENV['COMPANY_ADRESS'] ?? '1801 STEVENS AVE EAST PALO ALTO CA 94303-1264 USA';
    $companyPhone = $_ENV['COMPANY_PHONE'] ?? '(279) 987-1701';
    $year = date("Y");

    // Prétraitement et conversion du Markdown pour les CGV
    $cgvContent = file_exists($cgvPath) ? file_get_contents($cgvPath) : 'Contenu non disponible';
    $cgvContent = str_replace(
        ['{{ companyName }}', '{{ contactEmail }}', '{{ companyPhone }}', '{{ companyAdress }}'],
        [$companyName, $contactEmail, $companyPhone, $companyAdress],
        $cgvContent
    );
    $cgvContent = $parsedown->text($cgvContent);

    // Prétraitement et conversion du Markdown pour la politique de confidentialité
    $privacyContent = file_exists($privacyPath) ? file_get_contents($privacyPath) : 'Contenu non disponible';
    $privacyContent = str_replace(
        ['{{ companyName }}', '{{ contactEmail }}', '{{ companyPhone }}', '{{ companyAdress }}'],
        [$companyName, $contactEmail, $companyPhone, $companyAdress],
        $privacyContent
    );
    $privacyContent = $parsedown->text($privacyContent);


    // Récupérer le paramètre 'channel' et 'page' de l'URL
    $channel = $_GET['channel'] ?? 'default';
    $page = $_GET['page'] ?? 'home'; // Assurez-vous que "page" est correctement défini

    // Charger l'ordre des sections depuis le fichier YAML
    $section_order = Yaml::parseFile(__DIR__ . '/config/section_order.yaml');

    // Obtenir l'ordre des sections pour le "channel" et la page
    $order = $section_order['channels'][$channel][$page] ?? [];


    // Retourne les données
    return [
        'webrootURL' => $webrootURL,
        'companyName' => $companyName,
        'contactEmail' => $contactEmail,
        'companyPhone' => $companyPhone,
        'companyAdress' => $companyAdress,
        'surveyJsLocale' => $currentLocale,
        'cgvContent' => $cgvContent,
        'privacyContent' => $privacyContent,
        'year' => $year,
        'channel' => $channel,
        'order' => $order,
        'page' => $page,
    ];
}
