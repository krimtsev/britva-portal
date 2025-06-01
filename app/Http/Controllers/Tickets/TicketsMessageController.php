<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Ticket\TicketMessage;
use App\Models\Ticket\TicketCategory;
use App\Models\Partner;
use App\Models\Ticket\Ticket;

class TicketsMessageController extends Controller
{
    static function eventMessage(array $data_new, array $data_old, $ticket_id, $user) {
        $changes = [];

         if (
            array_key_exists('title', $data_new) &&
            array_key_exists('title', $data_old) &&
            $data_new['title'] != $data_old['title']
        ) {
            $changes[] = "тема запроса: {$data_old['title']} → {$data_new['title']}";
        }

        if (
            array_key_exists('category_id', $data_new) &&
            array_key_exists('category_id', $data_old) &&
            $data_new['category_id'] != $data_old['category_id']
        ) {
            $oldCategory = $data_old['category_id'] ? TicketCategory::find($data_old['category_id']) : null;
            $newCategory = $data_new['category_id'] ? TicketCategory::find($data_new['category_id']) : null;
            $oldCategoryTitle = $oldCategory ? $oldCategory->title : '—';
            $newCategoryTitle = $newCategory ? $newCategory->title : '—';
            $changes[] = "отдел: {$oldCategoryTitle} → {$newCategoryTitle}";
        }

        if (
            array_key_exists('partner_id', $data_new) &&
            array_key_exists('partner_id', $data_old) &&
            $data_new['partner_id'] != $data_old['partner_id']
        ) {
            $oldPartner = $data_old['partner_id'] ? Partner::find($data_old['partner_id']) : null;
            $newPartner = $data_new['partner_id'] ? Partner::find($data_new['partner_id']) : null;
            $oldPartnerName = $oldPartner ? $oldPartner->name : '—';
            $newPartnerName = $newPartner ? $newPartner->name : '—';
            $changes[] = "филиал: {$oldPartnerName} → {$newPartnerName}";
        }

        if (
            array_key_exists('state', $data_new) &&
            array_key_exists('state', $data_old) &&
            $data_new['state'] != $data_old['state']
        ) {
            $oldStateTitle = Ticket::getStateNameById($data_old['state']);
            $newStateTitle = Ticket::getStateNameById($data_new['state']);
            $changes[] = "статус: {$oldStateTitle} → {$newStateTitle}";
        }

        if (count($changes) == 0) return;

        $text = implode("\n", $changes);

        TicketMessage::create([
            'text'         => $text,
            'ticket_id'    => $ticket_id,
            'user_id'      => $user->id,
            'is_event'     => true,
        ]);
    }
}
