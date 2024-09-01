<?php

namespace Spacers\Framework\Controller;
use Spacers\Framework\Constant\Pattern\Singleton;

class AbstractController extends Singleton implements AbstractControllerInterface
{
    /**
     * Summary of json
     * @param mixed $data any input to encode as json string
     * @param array $headers list headers to set begin response
     * @param int $code status code response
     * @return \JsonSerializable
     */
    public function json($data, array $headers = [], int $code = 200): void
    {
        if (!array_key_exists("Content-Type", $headers)) {
            $headers["Content-Type"] = "application/json; charset=utf-8";
        }
        $this->close_connection_but_continue_processing(json_encode($data), $headers, $code);
    }
    private function close_connection_but_continue_processing($output, array $headers, int $code): void
    {
        if (function_exists('fastcgi_finish_request')) {

            echo $output;

            ignore_user_abort(true); //https://bugs.php.net/bug.php?id=68772
            fastcgi_finish_request();

        } else {
            ob_start();

            echo $output; //Quirk: At least one character must be outputted for the connection to be closed

            header("HTTP/3 $code");
            header('content-length: ' . ob_get_length());
            header('x-powered-by: Spacers Framework PHP/' . PHP_VERSION);
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }

            ob_end_flush();
            ob_flush();
            flush();
            session_write_close();
        }
    }
}