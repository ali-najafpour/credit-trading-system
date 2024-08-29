<?php

namespace App\Services;

class Responser
{
    public static function success($content = null, $title = null, $data = [], $statusCode = 200) {
        $content = $content ?: trans('messages.success_content');
        $title = $title ?: trans('messages.success_title');
        return response()->json([
            'data' => $data,
            'message' => [
                'title' => $title,
                'content' => $content
            ],
            'is_success' => true,
        ], $statusCode);
    }

    public static function error(array $errors = [], $data = [], $statusCode = 422) {
        $errors = !empty($errors) ? $errors: [trans('messages.error_title') => trans('messages.error_content')];
        return response()->json([
            'data' => $data,
            'errors' => $errors,
            'is_success' => false,
        ], $statusCode);
    }

    public static function data($data, $statusCode = 200) {
        return response()->json([
            'data' => $data,
            'is_success' => true,
        ], $statusCode);
    }
}
