<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanController extends Controller
{
    private $output;
    private $previousErrorHandler = false;

    public function __construct()
    {
        $this->output = new BufferedOutput;
    }

    /**
     * Funkce, která zachycuje chybu, která vzniká na hostingu Wedos
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool|mixed
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($errno == 2 && preg_match('/putenv\(\)/i', $errstr)) {
            return true;
        }
        if ($this->previousErrorHandler != null) {
            return call_user_func($this->previousErrorHandler, $errno, $errstr, $errfile, $errline);
        }
        return false;
    }

    /**
     * Funkce provádí příkazy Artisan
     *
     * @param $command
     * @param array $parameters
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function callArtisan($command, $parameters = [])
    {
        if ($this->previousErrorHandler === false) {
            $this->previousErrorHandler = set_error_handler([$this, 'errorHandler']);
        }

        $exit = Artisan::call($command, $parameters, $this->output);
        $outputText = "Command: $command\n---------\n";
        $outputText .= $this->output->fetch();
        $outputText .= "Exit code: " . intval($exit);

        return response($outputText, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Routy jsou nasměrovány na tuto funkci a podobné
     */
    public function down()
    {
        $this->callArtisan("down");
    }

    public function passportInstall() {
        $this->callArtisan("passport:install", ['--force' => true]);
    }
}