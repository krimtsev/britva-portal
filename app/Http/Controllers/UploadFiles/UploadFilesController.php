<?php

namespace App\Http\Controllers\UploadFiles;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFilesController extends Controller
{
    public static function addFile($folder, $upload_id, $file): void
    {
        $path = Storage::disk(UploadFile::FOLDER)->put($folder, $file);

        $origin = $file->getClientOriginalName();
        $ext = pathinfo($origin, PATHINFO_EXTENSION);
        $title = str_replace(".".$ext, "", $origin);

        UploadFile::create([
            "title"     => $title,
            "name"      => basename($path),
            "origin"    => $origin,
            "path"      => $path,
            "type"      => $file->getClientMimeType(),
            "ext"       => $ext,
            "upload_id" => $upload_id,
        ]);
    }

    public function edit(UploadFile $file)
    {
        $upload = Upload::select("id", "title")
            ->where("id", $file->upload_id)
            ->first();

        return view('dashboard.upload-files.edit', compact(
            'file',
            'upload'
        ));
    }

    public function update(UploadFile $file, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
        ]);

        $file->fill($validated);

        $file->save();

        return redirect()->route('d.upload.edit', $file->upload_id);
    }

    public function destroy(UploadFile $file)
    {
        Storage::disk(UploadFile::FOLDER)->delete($file->path);

        $file->delete();

        return redirect()->route('d.upload.edit', $file->upload_id);
    }

    public function download(Request $request)
    {
        $url = sprintf("%s/%s", $request->folder, $request->file);

        $exists = Storage::disk(UploadFile::FOLDER)->exists($url);

        if ($exists) {
            $file = UploadFile::select("title", "ext")
                ->where("name", $request->file)
                ->first();

            UploadFile::where("name", $request->file)
                ->increment("downloads");

            $name = sprintf("%s.%s", $file->title, $file->ext);

            return Storage::disk(UploadFile::FOLDER)->download($url, $name);
        }

        abort(404);
    }

}
