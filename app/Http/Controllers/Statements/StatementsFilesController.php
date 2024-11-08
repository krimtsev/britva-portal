<?php

namespace App\Http\Controllers\Statements;

use App\Http\Controllers\Controller;
use App\Models\Statement\StatementFile;
use App\Models\Upload;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StatementsFilesController extends Controller
{

    const RULES_ALLOW_TYPES = 'mimes:image,jpg,jpeg,png,tif,pdf,doc,docx,zip,xlsx,xls,txt,ai,pptx';

    public static function addFile($folder, $statement_message_id, $file): void
    {
        $path = Storage::disk(StatementFile::FOLDER)->put($folder, $file);

        $origin = $file->getClientOriginalName();
        $ext = pathinfo($origin, PATHINFO_EXTENSION);
        $title = str_replace(".".$ext, "", $origin);

        StatementFile::create([
            "title"     => $title,
            "name"      => basename($path),
            "origin"    => $origin,
            "path"      => $path,
            "type"      => $file->getClientMimeType(),
            "ext"       => $ext,
            "statement_message_id" => $statement_message_id,
        ]);
    }

    public function download(Request $request)
    {
        $url = sprintf("%s/%s", $request->folder, $request->file);

        $exists = Storage::disk(StatementFile::FOLDER)->exists($url);

        if ($exists) {
            $file = StatementFile::select("title", "ext")
                ->where("name", $request->file)
                ->first();

            $name = sprintf("%s.%s", $file->title, $file->ext);

            return Storage::disk(StatementFile::FOLDER)->download($url, $name);
        }

        abort(404);
    }

}
