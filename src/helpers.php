<?php
/**
 * Get host location http(s)://host
 * @return string
 */
function get_host_location(): string
{
    return explode(
        "/",
        strtolower($_SERVER['SERVER_PROTOCOL'])
    )[0] . '://' . $_SERVER['HTTP_HOST'];
}
/**
 * Summary of spacers_exception_handler
 * @param Throwable $exception
 * @return void
 */
function spacers_exception_handler(Throwable $exception): void
{
    dd($exception);
}

/**
 * Summary of default_environments
 * @param array $environments
 * @return array
 */
function set_default_environments(array $environments): array
{
    $environments["SPACERS_PROJECT_DIR"] = realpath(getcwd() . "/../");
    $environments["APP_DEBUG"] = $environments["APP_DEBUG"] ?? 0;
    $environments["APP_ENV"] = $environments["APP_ENV"] ?? "production";

    foreach ($environments as $key => $value) {
        putenv($key . "=" . $value);
    }

    return $environments;
}
/**
 * Summary of is_debug
 * @return bool
 */
function is_debug(): bool
{
    return (bool) getenv("APP_DEBUG");
}
/**
 * Summary of json_validate
 * @param string $string
 * @return bool
 */
function json_validate(string $string): bool
{
    json_decode($string);

    return json_last_error() === JSON_ERROR_NONE;
}

function render_template(string $fimename, array $attributes = []): string
{
    foreach ($attributes as $key => $value) {
        $$key = $value;
    }
    try {
        ob_start();
        require $fimename;
        $content = ob_get_clean();
        flush();
        return $content;
    } catch (\Throwable $th) {
        throw new \Exception("Templat error", 0, $th);
        exit(0);
    }
}
