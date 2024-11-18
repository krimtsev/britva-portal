<?php

namespace App\Http\Controllers\Tickets;

class TicketsQuestions
{
    public const templates = [
        "key" => [
            "category_id" => 1,
            "questions" => [
                [
                    "key"  => "question_1",
                    "text" => "Все нормально?",
                    "rules" => ["required", "string"],
                ],
                [
                    "key"   => "question_2",
                    "text"  => "Это второй вопрос?",
                    "rules" => ["required", "string"]
                ]
            ],
        ],
    ];

    public static function getData($key): array
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key];
        }
        return [];
    }

    public static function getCategory($key)
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key]["category_id"];
        }
        return null;
    }

    public static function getQuestions($key)
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key]["questions"];
        }
        return [];
    }

    public static function isExist($key): bool
    {
        return array_key_exists($key, self::templates);
    }
}
