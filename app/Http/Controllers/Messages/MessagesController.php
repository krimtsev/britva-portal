<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessagesController extends Controller
{
    const URL = "https://britva.tech/messages.php";

    const TEST_TG_ID = "-1001993054003";

    private function getPartners() {
        return Partner::select(
            "name",
            "tg_chat_id",
        )
            ->where('yclients_id', '<>', "")
            ->where('disabled', '<>', 1)
            ->where("tg_active", 1)
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

    public function index()
    {
        $partners = self::getPartners();

        return view("dashboard.messages.index", compact(
            "partners",
        ));
    }

    public function send(Request $request)
    {
        $request->validate([
            'description' => 'string|min:10',
        ]);

        try {
            $tg_ids = [];

            switch ($request->selected_partners) {
                case "test":
                    $tg_ids[] = self::TEST_TG_ID;;
                    break;
                case "all":
                    $tg_ids = self::getUniqChatIds();
                    break;
                default:
                    $tg_ids[] = $request->selected_partners;
            }

            $body = [
                "msg"     => $request->description,
                "tg_ids"  => $tg_ids,
                "partner" => env('PARTNER_NAME', '')
            ];

            Http::withOptions([
                "verify" => false,
            ])
                ->asForm()
                ->post(self::URL, $body);

            return redirect()->route('d.messages.index');
        } catch (Throwable $e) {
            report($e->getMessage());
        }
    }
}
