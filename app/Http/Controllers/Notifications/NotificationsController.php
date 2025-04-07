<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Messages\MessagesController;
use App\Models\Partner;

class NotificationsController extends Controller
{
    const IGNORE = [
        "-1002027438836", // britva
        "-1002037086197"  // soda
    ];

    private function getPartners() {
        return Partner::select(
            "name",
            "tg_chat_id",
        )
            ->where('yclients_id', '<>', "")
            ->where('disabled', '<>', 1)
            ->where("tg_active", 1)
            ->whereNotIn("tg_chat_id", self::IGNORE)
            ->orderBy("name")
            ->get();
    }

    private function getUniqChatIds(): array
    {
        $partners = self::getPartners()
            ->groupBy('tg_chat_id')
            ->toArray();

        return array_keys($partners);
    }

    /**
     * Еженедельные видео отчеты телеграм
     */
    public function videoReports() {
        $ids = ["-1001993054003"]; // $this->getUniqChatIds();
        $msg = join("\n", [
            "Не забудьте отправить еженедельный видеоотчет по филиалу пользователю @britva_otchet до 16:00 каждого понедельника.",
            "Напоминаем, штраф за пропущенный видеоотчет для филиала - 5000 рублей.",
            "Если отчет уже отправлен, сообщи об этом в комментарии под этой публикацией."
        ]);

        MessagesController::handler($msg, $ids);
    }
}
