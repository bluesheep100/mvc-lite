<?php

use App\Routing\Response;

function dd(...$vars)
{
    ob_start();
    print_r($vars);
    $dump = ob_get_clean();

    echo <<<HTML
        <pre>
            {$dump}
        </pre>
    HTML;
    die;
}

function view($name, $params = [])
{
    extract($params);
    ob_start();
    @include(sprintf('%s/%s.php', VIEW_DIR, $name));

    return ob_get_clean();
}

function url($path, $params = [])
{
    $path = ltrim($path, '/');
    // Determine protocol
    $https = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
    // Get website domain
    $domain = $_SERVER['SERVER_NAME'];
    // Create URL
    $url = $https . $domain . '/' .  $path;

    // Attach query string, if any
    if ($params) {
        $url .= '?'.http_build_query($params);
    }

    return $url;
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit();
}

function formatRoute($route, $stripQueryString = false)
{
    if ($stripQueryString) {
        $route = preg_replace('/\/(?=$|\?)|\?.+/', '', $route);
    }

    return '/' . trim($route, '/ ');
}

function toCamelCase($str)
{
    // Ignore strings that begin with an underscore
    if (preg_match('/^_/', $str)) {
        return $str;
    }

    $str = str_replace(
        '_',
        '',
        ucwords(strtolower($str),'_')
    );
    $str = lcfirst($str);

    return $str;
}

function response($status, $body = [])
{
    http_response_code($status);
    return new Response($status, $body);
}
