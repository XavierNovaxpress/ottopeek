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

// VARIABLES GLOBALES POUR LE PROJET
function getGlobalData(Translator $translator)
{
    $parsedown = new Parsedown();
    $currentLocale = substr($translator->getLocale(), 0, 2); // Extrait le code de langue (ex. 'fr')

    // Récupérer le paramètre 'channel' de l'URL
    $channel = $_GET['channel'] ?? 'default';

    // Charger l'ordre des sections depuis le fichier YAML
    $section_order = Yaml::parseFile(__DIR__ . '/config/section_order.yaml');


    // Détermination des chemins des fichiers Markdown
    $cgvPath = __DIR__ . "/translations/markdown/cgv-{$currentLocale}.md";
    $privacyPath = __DIR__ . "/translations/markdown/privacy-{$currentLocale}.md";

    // Définition des variables
    $webrootURL = (strpos(__DIR__, 'mamp') !== false) ? "https://ottopeek-front.mamp:8890" : "";
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
    ];
}
// VARIABLES GLOBALES POUR LE PROJET

// Chargement des variables d'environnement depuis le fichier .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/templates');

// Configuration de la localisation
$defaultLocale = $_ENV['LOCALE'] ?? 'fr';
$translator = new Translator($defaultLocale);
$translator->addLoader('yaml', new YamlFileLoader());

// Chargement des ressources de traduction
$translator->addResource('yaml', __DIR__ . '/translations/messages.fr.yaml', 'fr');
$translator->addResource('yaml', __DIR__ . '/translations/messages.en.yaml', 'en');
$translator->addResource('yaml', __DIR__ . '/translations/messages.it.yaml', 'it');
$translator->addResource('yaml', __DIR__ . '/translations/messages.es.yaml', 'es');
$translator->addResource('yaml', __DIR__ . '/translations/messages.de.yaml', 'de');

$twig->getEnvironment()->addExtension(new TranslationExtension($translator));


// Middleware pour la gestion de la locale
$localeMiddleware = function ($request, $handler) use ($translator, $twig, $defaultLocale) {
    $queryParams = $request->getQueryParams();
    $locale = $queryParams['lang'] ?? $defaultLocale;
    $allowedLocales = ['fr', 'en', 'it', 'es', 'de'];

    if (!in_array($locale, $allowedLocales)) {
        $locale = $defaultLocale;
    }

    $translator->setLocale($locale);
    $twig->getEnvironment()->addGlobal('locale', $locale);

    return $handler->handle($request);
};

$app->add($localeMiddleware);
$app->add(TwigMiddleware::create($app, $twig));

// ROUTING
$app->get('/', function ($request, $response, $args) use ($twig, $translator) {
  $globalData = getGlobalData($translator);
  return $twig->render($response, '_index.twig', $globalData);
});

$app->get('/pricing', function ($request, $response, $args) use ($twig, $translator) {
  $globalData = getGlobalData($translator);
  return $twig->render($response, '_pricing.twig', $globalData);
});

$app->get('/checkout', function ($request, $response, $args) use ($twig, $translator) {
  $globalData = getGlobalData($translator);
  return $twig->render($response, '_checkout.twig', $globalData);
});

$app->get('/payment', function ($request, $response, $args) use ($twig, $translator) {
  $globalData = getGlobalData($translator);
  return $twig->render($response, '_payment.twig', $globalData);
});

$app->get('/legals', function ($request, $response, $args) use ($twig, $translator) {
  $globalData = getGlobalData($translator);
  return $twig->render($response, '_legals.twig', $globalData);
});

$app->run();
