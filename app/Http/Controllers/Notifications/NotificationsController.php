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

    private function getPartners(bool $isIgnore = false) {
        $query = Partner::select(
            "name",
            "tg_chat_id",
        )
            ->where('yclients_id', '<>', "")
            ->where('disabled', '<>', 1)
            ->where("tg_active", 1);

        if ($isIgnore) {
            $query->whereNotIn("tg_chat_id", self::IGNORE);
        }

        return $query->orderBy("name")
            ->get();
    }

    private function getUniqChatIds(bool $isIgnore = false): array
    {
        $partners = self::getPartners($isIgnore)
            ->groupBy('tg_chat_id')
            ->toArray();

        return array_keys($partners);
    }

    /**
     * Видео напоминание
     */
    public function videoMessage() {
        $ids = $this->getUniqChatIds(true);
        $msg = join("\n\n", [
            "🔔 Напоминание:",
            "Не забудьте отправить <b>еженедельный видеоотчет по филиалу</b> пользователю @britva_otchet <b>до 16:00 каждого понедельника</b>.",
            "💸 Штраф за пропущенный отчет — <b>5000 рублей</b>.",
            "✅ Если отчет уже отправлен — <b>отметь это в комментариях</b> под этой публикацией.",
        ]);

        MessagesController::handler($msg, $ids);
    }

    /**
     * Напоминания о боте
     */
    public function whatsappBotMessage() {
        $ids = $this->getUniqChatIds();
        $msg = join("\n", [
            "👋 Коллеги, привет! Это напоминалка  о работе Wahelp.\n",
            "После этого уведомления выполните, пожалуйста, следующие шаги:\n",
            "- ППроверьте, что ваш WhatsApp-бот работает, и отправьте сообщение самому себе.",
            "- Убедитесь, что все диалоги с клиентами завершены или получен ответ.",
            "- В течение дня следите за тем, чтобы бот не отключался и всё работало стабильно.",
            "Спасибо вам и хороших касс! 💸\n",
            "🟢 Отпишитесь в комментариях или поставьте реакцию, чтобы было видно, что вы прочитали это сообщение.",
        ]);

        MessagesController::handler($msg, $ids);
    }
}
