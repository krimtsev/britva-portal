<?php

namespace App\Http\Controllers\Upload;


use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadFiles\UploadFilesController;
use App\Http\Requests\UploadFile\UploadStoreRequest;
use App\Http\Requests\UploadFile\UploadUpdateRequest;
use App\Models\UploadCategories;
use App\Models\Upload;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.upload.index', compact('uploads'));
    }

    public function create()
    {
        $categories = UploadCategories::select("id", "name")->get();

        return view('dashboard.upload.create', compact('categories'));
    }

    public function store(UploadStoreRequest $request)
    {
        $validated = $request->validated();

        $folder = Str::orderedUuid();

        $upload = Upload::create([
            "title"       => $validated["title"],
            "folder"      => $folder,
            "category_id" => $validated["category"],
        ]);

        if (array_key_exists('files', $validated)) {
            foreach ($validated['files'] as $file) {
                UploadFilesController::addFile($folder, $upload->id, $file);
            }
        }

        return redirect()->route('d.upload.index');
    }

    public function edit(Upload $upload)
    {
        $categories = UploadCategories::select("id", "name")->get();
        $files = UploadFile::select("id", "name", "downloads", "title", "ext")
            ->where("upload_id", $upload->id)
            ->get();

        foreach ($files as $file) {
            $file["url"] = URL::signedRoute('upload.download', [
                "folder" => $upload->folder,
                "file"   => $file->name
            ]);
        }

        return view('dashboard.upload.edit', compact(
            "upload",
            "categories",
            "files"
        ));
    }

    public function update(UploadUpdateRequest $request, Upload $upload)
    {
        $validated = $request->validated();

        $upload->fill([
            "title"       => $validated["title"],
            "category_id" => $validated["category"],
        ]);

        $upload->save();

        if (array_key_exists('files', $validated)) {
            foreach ($validated['files'] as $file) {
                UploadFilesController::addFile($upload->folder, $upload->id, $file);
            }
        }

        return redirect()->route('d.upload.index');
    }

    public function destroy(Upload $upload)
    {
        // TODO: Добавить логику удаления записи и связанных файлов
        // $file->delete();
        // return redirect()->route('d.upload.index');
    }

    public function show(Request $request)
    {
        return view('cloud.index');
    }
}
