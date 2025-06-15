<?php

use Illuminate\Support\Facades\Lang;

/**
 * for translation
 *
 * @param  string  $key
 * @param  mixed  $placeholder
 * @param  null|mixed  $locale
 * @return string.
 */
function api($key, $placeholder = [], $locale = null)
{

    $group = 'api';
    if (is_null($locale)) {
        $locale = config('app.locale');
    }
    $key = trim($key);
    $word = $group.'.'.$key;
    if (Lang::has($word)) {
        return trans($word, $placeholder, $locale);
    }

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = ['ar', 'en'];
    foreach ($langs as $lang) {
        $translation_file = base_path().'/resources/lang/'.$lang.'/'.$group.'.php';
        $fh = fopen($translation_file, 'r+');
        $new_key = "  \n  '$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
    }

    return trans($word, $placeholder, $locale);
}

/**
 * for translation
 *
 * @param  string  $key
 * @param  mixed  $placeholder
 * @param  null|mixed  $locale
 * @return string.
 */
function admin($key, $placeholder = [], $locale = null)
{

    $group = 'admin';
    if (is_null($locale)) {
        $locale = config('app.locale');
    }
    $key = trim($key);
    $word = $group.'.'.$key;
    if (Lang::has($word)) {
        return trans($word, $placeholder, $locale);
    }

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = ['ar', 'en'];
    foreach ($langs as $lang) {
        $translation_file = base_path().'/resources/lang/'.$lang.'/'.$group.'.php';
        $fh = fopen($translation_file, 'r+');
        $new_key = "  \n  '$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
    }

    return trans($word, $placeholder, $locale);
}

/**
 * for translation
 *
 * @param  string  $key
 * @param  mixed  $placeholder
 * @param  null|mixed  $locale
 * @return string.
 */
function email($key, $placeholder = [], $locale = null)
{

    $group = 'email';
    if (is_null($locale)) {
        $locale = config('app.locale');
    }
    $key = trim($key);
    $word = $group.'.'.$key;
    if (Lang::has($word)) {
        return trans($word, $placeholder, $locale);
    }

    $messages = [
        $word => $key,
    ];

    app('translator')->addLines($messages, $locale);
    $langs = ['ar', 'en'];
    foreach ($langs as $lang) {
        $translation_file = base_path().'/resources/lang/'.$lang.'/'.$group.'.php';
        $fh = fopen($translation_file, 'r+');
        $new_key = "  \n  '$key' => '$key',\n];\n";
        fseek($fh, -4, SEEK_END);
        fwrite($fh, $new_key);
        fclose($fh);
    }

    return trans($word, $placeholder, $locale);
}

/**
 * get current language
 *
 * @return string.
 */
function currentLanguage()
{
    return app()->getLocale();
}

/**
 * code generation
 *
 * @param  int  $length
 * @return string.
 */
if (! function_exists('generateRandomVerificationCode')) {
    function generateRandomVerificationCode($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
/**
 * String generation
 *
 * @param  int  $length
 * @return string.
 */
function generateRandomString($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

/**
 * available pagination size
 *
 * @param \Illuminate\Http\Request
 */
function currentApiPageSize(): int
{
    $pageSize = request()->per_page ?? 10;

    return $pageSize;
}

/**
 * for Pagination Object
 *
 * @param  object  $object
 * @return array.
 */
function paginate($object)
{
    return [
        'current_page' => (string) $object->currentPage(),
        // 'items' => $object->items(),
        'first_page_url' => (string) $object->url(1),
        'from' => (string) $object->firstItem(),
        'last_page' => (string) $object->lastPage(),
        'last_page_url' => (string) $object->url($object->lastPage()),
        'next_page_url' => (string) $object->nextPageUrl(),
        'per_page' => (string) $object->perPage(),
        'prev_page_url' => (string) $object->previousPageUrl(),
        'to' => (string) $object->lastItem(),
        'total' => (string) $object->total(),
    ];
}
