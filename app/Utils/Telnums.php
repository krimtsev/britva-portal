<?php

namespace App\Utils;

class Telnums
{
    static public function getTelnumsList() {
        $partnerName = env('PARTNER_NAME', '');
        $folder = sprintf("mango/%s/telnums.json", $partnerName);

        $list = storage_path($folder);

        if (is_string($list)) {
            if (!file_exists($list)) {
                throw new Error(sprintf('file "%s" does not exist', $list));
            }

            $json = file_get_contents($list);

            if (!$list = json_decode($json, true)) {
                throw new Error('invalid json for auth config');
            }

            return $list;
        }

        return [];
    }

    public function getPartnersList(): array
    {
        $list = self::getTelnumsList();
        $result = [];

        foreach ($list as $value) {
            $result[$value['tg_chat_id']][] = [
                "name"       => $value["name"],
                "isActive"   => array_key_exists("active", $value) ? $value["active"] : false,
            ];
        }

        return $result;
    }
}
