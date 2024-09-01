<?php

namespace Spacers\Framework\Controller;
use Spacers\Framework\Constant\Pattern\Singleton;
use Spacers\Framework\Exception\NotFoundExcetion;

class AbstractController extends Singleton implements AbstractControllerInterface
{
    /**
     * Summary of send
     * @param string $text any input to encode as text string 
     * @param array $headers list headers to set begin response
     * @param int $code status code response
     * @return void
     */
    public function send(string $text, array $headers = [], int $code = 200): void
    {
        $this->flush_content_buffer_processing($text, $headers, $code);
    }
    /**
     * Summary of json
     * @param mixed $data any input to encode as json string
     * @param array $headers list headers to set begin response
     * @param int $code status code response
     * @return \JsonSerializable
     */
    public function json($data, array $headers = [], int $code = 200): void
    {
        $this->flush_content_buffer_processing(json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE), $headers, $code);
    }

    /**
     * Summary of render
     * @param string $template filename template end with *.tpl.php
     * @param array $proprieties proprieties to inject into template
     * @param array $headers list headers to set begin response
     * @param int $code status code response
     * @return void
     */
    public function render(string $template, array $proprieties = [], array $headers = [], int $code = 200): void
    {
        $template_path = getenv("SPACERS_PROJECT_DIR") . "/templates";

        if (!is_dir($template_path)) {
            throw new NotFoundExcetion("Template directory '$template_path' not found.");
        }
        if (!file_exists("$template_path/$template")) {
            throw new NotFoundExcetion("Template '$template_path/$template' not found.");
        }

        $this->flush_content_file_processing("$template_path/$template", $proprieties, $headers, $code);
    }

    /**
     * Summary of update_headers
     * @param array $headers
     * @param string[] $attribute
     * @return array
     */
    private function update_headers(array $headers, ...$attribute): array
    {
        array_push($headers, ...$attribute);
        return $headers;
    }

    private function flush_content_file_processing(string $template, array $proprieties, array $headers, int $code): void
    {
 

        ob_start();


        foreach ($proprieties as $key => $value) {
            $$key = $value;
        }

        require $template;

        header("HTTP/3 $code");
        header("content-length: " . ob_get_length());
        header("x-powered-by: Spacers Framework PHP/" . PHP_VERSION);

        $fi = new \finfo(FILEINFO_MIME_TYPE);
        $headers = $this->update_headers(
            $headers,
            "Content-Type: " . $fi->file($template)
        );
        foreach ($headers as $value) {
            header("$value");
        }

        ob_end_flush();
        ob_flush();
        flush();
        session_write_close();
    }
    private function flush_content_buffer_processing($output, array $headers, int $code): void
    {
        ob_start();

        $fi = new \finfo(FILEINFO_MIME);

        echo $output;

        header("HTTP/3 $code");
        header("content-length: " . ob_get_length());
        header("x-powered-by: Spacers Framework PHP/" . PHP_VERSION);

        $headers = $this->update_headers(
            $headers,
            "Content-Type: " . $fi->buffer($output)
        );
        dump($headers);
        foreach ($headers as $value) {
            header("$value");
        }

        ob_end_flush();
        ob_flush();
        flush();
        session_write_close();
    }
}