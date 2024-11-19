<?php

namespace App\Http\Controllers\Tickets;

class TicketsQuestions
{
    public const templates = [
        "maket" => [
            "category_id" => 7,
			"title" => "Заявка на макет",
            "description" => [
                "Регламент для занесения:",
                "1. ...",
                "2. ...",
            ],
            "questions" => [
                [
                    "text" => "Контактный номер телефона и имя?",
                    "description" => [
                        "Регламент для занесения:",
                        "1. ...",
                        "2. ...",
                    ],
                    "rules" => ["required", "string"],
                ],
				[
                    "text"  => "Укажите требования к макету. Формат, для чего будет использоватьяс, необходимый размер и цвета? \nЕсли Вам необходим стартовый набор, напишите в поле СТАРТОВЫЙ НАБОР.",
                    "rules" => ["required", "string"]
                ],
				[
                    "text"  => "Укажите данные филиала. Ссылка на страницу сайта, ссылка на онлайн-запись, ссылка на Яндекс.Карты и 2ГИС, а так же Instagram.",
                    "rules" => ["required", "string"]
                ],
				[
                    "text"  => "Укажите условия акции. Промокод, срок действия акции, размер скидки? \nЕсли Вам необходим стартовый набор, напишите в поле СТАРТОВЫЙ НАБОР.",
                    "rules" => ["required", "string"]
                ]
            ],
        ],
    ];

    public static function getData($key): array
    {
        if (array_key_exists($key, self::templates)) {
            $template = self::templates[$key];

            $template["questions"] = array_map(function($question, $index) {
                $question["key"] = sprintf("question_%s", $index);
                return $question;
            }, $template["questions"], array_keys($template["questions"]));

            return $template;
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

    public static function getTitle($key)
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key]["title"];
        }
        return null;
    }

    public static function getDescription($key)
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key]["description"];
        }
        return null;
    }

    public static function getQuestions($key)
    {
        if (array_key_exists($key, self::templates)) {
            $template = self::getData($key);
            return $template["questions"];
        }
        return [];
    }

    public static function isExist($key): bool
    {
        return array_key_exists($key, self::templates);
    }
}
