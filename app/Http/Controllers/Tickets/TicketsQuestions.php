<?php

namespace App\Http\Controllers\Tickets;

class TicketsQuestions
{
    public const templates = [
        "maket" => [
			"title" => "Заявка на макет",
            "category_id" => 7,
            "questions" => [
                [
                    "key"  => "q1",
                    "text" => "Контактный номер телефона и имя?",
                    "description" => [
                        "Регламент для занесения:",
                        "1. ...",
                        "2. ...",
                        "3. ...",
                    ],
                    "rules" => ["required", "string"],
                ],
				[
                    "key"   => "q2",
                    "text"  => "Укажите требования к макету. Формат, для чего будет использоватьяс, необходимый размер и цвета? \nЕсли Вам необходим стартовый набор, напишите в поле СТАРТОВЫЙ НАБОР.",
                    "rules" => ["required", "string"]
                ],
				[
                    "key"   => "q3",
                    "text"  => "Укажите данные филиала. Ссылка на страницу сайта, ссылка на онлайн-запись, ссылка на Яндекс.Карты и 2ГИС, а так же Instagram.",
                    "rules" => ["required", "string"]
                ],
				[
                    "key"   => "q4",
                    "text"  => "Укажите условия акции. Промокод, срок действия акции, размер скидки? \nЕсли Вам необходим стартовый набор, напишите в поле СТАРТОВЫЙ НАБОР.",
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

    public static function getTitle($key)
    {
        if (array_key_exists($key, self::templates)) {
            return self::templates[$key]["title"];
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
