<?php
require 'bower_components/Slim/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

require 'services/config.php';
$config = GitWebAdmin\Config::fromFile('config.ini');

$app->get('/', function () use ($app) {
    global $config;
    $repo_list = get_repo_list();
    $vars = compact('config', 'repo_list');
    $app->render('index.tpl.php', $vars);
});

$app->post('/create', function () use ($app) {

    $repository = $app->request()->post('repository');
    $repo_name = trim($app->request()->post('repo_name'));

    if (basename($repo_name) == "" || substr($repo_name, -1) == '/' || (isWin() && substr($repo_name, -1) == '\\')) {
        $app->halt(400, 'Repository name is empty');
    }

    // Note: on Windows, both slash (/) and backslash (\) are used as directory separator character. In other environments, it is the forward slash (/).
    if (strpos($repo_name, "../") !== false || (isWin() && strpos($repo_name, "..\\") !== false)) {
        $app->halt(403, 'Obvious attempt to look behind the curtain');
    }

    if (strcasecmp(substr($repo_name, -4), '.git') != 0) {
        $repo_name .= '.git';
    }

    $real_path = get_real_path($app, $repository, $repo_name);

    if (isWin()) {
        $cmd = ".\\git-init.cmd";
    } else {
        $cmd = "./git-init.sh";
    }

    execute_command($app, $cmd . " \"{$real_path}\"", function ($text, $error, $status) use ($app) {
        if ($status['exitcode'] > 0) {
            $app->halt(400, ($error != "" ? $error : $text));
        } else {
            $app->status(201);
            $app->response()->setBody(json_encode(array('status' => 'success', 'message' => $text)));
            return $app->response();
        }
    });
});

$app->post('/delete', function () use ($app) {

    $repository = $app->request()->post('repository');
    if ($repository == null )
    {
        $app->halt(400, 'Repository is empty');
    }

    $repo_name = $app->request()->post('repo_name');
    if ($repo_name == null )
    {
        $app->halt(400, 'Repository name is empty');
    }

    $real_path = get_real_path($app, $repository, $repo_name);

    if (isWin()) {
        $cmd = ".\\git-rm.cmd";
    } else {
        $cmd = "./git-rm.sh";
    }

    execute_command($app, $cmd . " \"{$real_path}\"", function ($text, $error, $status) use ($app) {

        if ($status['exitcode'] > 0) {
            $app->halt(400, ($error != "" ? $error : $text));
        } else {
            $app->status(202);
            $app->response()->setBody(json_encode(array('status' => 'success', 'message' => $text)));
            return $app->response();
        }
    });
});

$app->get('/list', function () use ($app) {
    $repo_list = get_repo_list();

    $body = array();
    foreach ($repo_list as $key => $repo) {
        $body[$key] = array();
        foreach ($repo as $path => $dir) {
            $body[$key][] = $repo->getSubPathName();
        }
    }
    $app->contentType("application/json");
    $app->response()->setBody(json_encode($body));
    return $app->response();
});

$app->run();

function get_real_path($app, $repository, $repo_name) {
    global $config;
    $repositories = $config->get('git', 'repositories');

    if ($repositories == null  || !is_array($repositories) || count($repositories) == 0) {
        $app->halt(500, 'Please, edit the config file and provide your repositories directory');
    }

    if ($repository !== null) {
        if (in_array($repository, $repositories)) {
            return add_ending_slash($repository) . $repo_name;
        }
        else {
            $app->halt(404, 'Repository not found');
        }
    } else {
        return add_ending_slash($repositories[0]).$repo_name;
    }
}

// List repositories
function get_repo_list()
{
    global $config;
    $repositories = $config->get('git', 'repositories');

    $file_list = array();
    foreach ($repositories as $directory) {

        // We want to iterate a directory
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);

        // Ignore "Permission denied"
        $recursiveIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);

        $filter = new RegexIterator($recursiveIterator, '/.(\.git)$/i', RecursiveRegexIterator::GET_MATCH);

        $file_list[$directory] = $filter;
    }
    ksort($file_list);
    return $file_list;
}

// Execute a command
function execute_command($app, $cmd, $callback)
{
    if (!function_exists('proc_open')) {
        $app->halt(500, 'Missed php function: proc_open');
    }
    if (!function_exists('stream_get_contents')) {
        $app->halt(500, 'Missed php function: stream_get_contents');
    }

    $descriptor = array(
        0 => array("pipe", "r"), // stdin is a pipe that the child will read from
        1 => array("pipe", "w"), // stdout is a pipe that the child will write to
        2 => array("pipe", "w")  // stderr is a pipe that the child will write to
    );

    $process = proc_open($cmd, $descriptor, $pipes, realpath('.'), NULL, array('binary_pipes'));

    if (is_resource($process)) {

        // fwrite($pipes[0], $input);
        fclose($pipes[0]);

        // Read from the pipe
        $text = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read from the pipe
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $status = proc_get_status($process);

        $close_status = proc_close($process);

        // TODO: How to set default charset in Windows
        // mb_convert_encoding($text, 'utf-8', mb_detect_encoding($text))
        // iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text)
        $callback($text, $error, $status, $close_status);
    } else {
        $app->halt(500, 'Command execution failed, can not find the executable program: ' . $cmd);
    }
}

// Add the ending slash
function add_ending_slash($path)
{
    if (substr($path, (0 - (int)strlen(DIRECTORY_SEPARATOR))) !== DIRECTORY_SEPARATOR) {
        $path .= DIRECTORY_SEPARATOR;
    }
    return $path;
}

// Checking OS Windows
function isWin()
{
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}