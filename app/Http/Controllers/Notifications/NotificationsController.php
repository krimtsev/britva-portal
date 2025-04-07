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
        $ids = ["-1001993054003"]; /// $this->getUniqChatIds();
        $msg = join("\n\n", [
            "🔔 Напоминание:",
            "Не забудьте отправить <b>еженедельный видеоотчет по филиалу</b> пользователю @britva_otchet <b>до 16:00 каждого понедельника</b>.",
            "💸 Штраф за пропущенный отчет — <b>5000 рублей</b>.",
            "✅ Если отчет уже отправлен — <b>отметь это в комментариях</b> под этой публикацией.",
        ]);

        MessagesController::handler($msg, $ids);
    }
}
