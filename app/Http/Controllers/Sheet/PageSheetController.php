<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;
use App\Http\Services\GoogleSheetService;

class PageSheetController extends Controller
{

    private function getDescription($key): string
    {
        $description = [
            "find-certificate" => 'В данной таблице представлена информация по отгрузке (куда и какой пластик поехал). Не пытайтесь искать здесь сертификаты проданные через сайт.
            <br />Если не получается найти обычный сертификат, пишите сразу <a target="_blank" href="https://wa.me/79994845317">Диме</a>, он посмотрит где продавался по Yclients.
            <br />Если не удается оплатить визит по сертификату с сайта (купленный онлайн), то пишите сразу <a target="_blank" href="https://wa.me/79652914902">Артему</a>.',

            "contact-outstaff" => ''
        ];

        return array_key_exists($key, $description)
            ? $description[$key]
            : "";
    }

    public function __invoke($slug)
    {

        $sheet = Sheet::where('slug', '=', $slug)
            ->where('is_published', '=', 1)
            ->firstOrFail();

        $table = (new GoogleSheetService)->readSheet($sheet);

        foreach ($table as $key => $value) {
            if(count($table[0]) != count($value))
                unset($table[$key]);
        }

        $sheet->table = $table;

        if ($sheet->table_header) {
            $sheet->table_header = explode(',', $sheet->table_header);
        }

        $description = self::getDescription($slug);

        return view('sheet.index', compact(
            'sheet',
            'description'
        ));
    }
}


