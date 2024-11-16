<?php

namespace App\Http\Controllers\Upload;


use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFile\UploadStoreRequest;
use App\Http\Requests\UploadFile\UploadUpdateRequest;
use App\Models\Upload;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::whereNull('category_id')
            ->with('children')
            ->orderBy("id", "ASC")
            ->get();

        return view("dashboard.upload.index", compact("uploads"));
    }

    public function table()
    {
        $uploads = Upload::orderBy("id", "DESC")->paginate(30);

        return view("dashboard.upload.table", compact("uploads"));
    }

    public function create()
    {
        $categories = Upload::select("id", "name")
            ->get();

        return view("dashboard.upload.create", compact("categories"));
    }

    public function store(UploadStoreRequest $request)
    {
        $validated = $request->validated();

        $upload = Upload::create([
            "name"          => $validated["name"],
            "slug"          => $validated["slug"],
            "category_id"   => $validated["category_id"],
            "folder"        => Str::orderedUuid(),
        ]);

        if (array_key_exists("files", $validated)) {
            foreach ($validated["files"] as $file) {
                UploadFilesController::addFile($upload->folder, $upload->id, $file);
            }
        }

        return redirect()->route("d.upload.update", $upload->id);
    }

    public function edit(Upload $upload)
    {
        $categories = Upload::whereNull('category_id')
            ->with('children')
            ->get();

        $files = UploadFile::select("id", "name", "downloads", "title", "ext")
            ->where("upload_id", $upload->id)
            ->get();

        foreach ($files as $file) {
            $file["url"] = URL::signedRoute("upload.download", [
                "folder" => $upload->folder,
                "file"   => $file->name
            ]);
        }

        return view("dashboard.upload.edit", compact(
            "upload",
            "categories",
            "files"
        ));
    }

    public function update(UploadUpdateRequest $request, Upload $upload)
    {
        $validated = $request->validated();

        $upload->fill([
            "name"         => $validated["name"],
            "slug"         => $validated["slug"],
        ]);

        $upload->save();

        if (array_key_exists("files", $validated)) {
            foreach ($validated["files"] as $file) {
                UploadFilesController::addFile($upload->folder, $upload->id, $file);
            }
        }

        return redirect()->route("d.upload.update", $upload->id);
    }

    public function destroy(Upload $upload)
    {
        // TODO: Добавить логику удаления записи и связанных файлов
        if (count($upload->children) != 0) {
            return back()->withErrors('Нельзя удалить вложенную категорию');
        }

        foreach ($upload->files as $file) {
            $file->delete();
        }

        $upload->delete();

        Storage::disk(UploadFile::FOLDER)->deleteDirectory($upload->folder);

        return redirect()->route("d.upload.index");
    }

    public function show(Request $request)
    {
        $slug = $request->route('slug');

        $db = Upload::select(
            "id",
            "name",
            "slug",
            "folder",
            "category_id"
        );

        if ($slug) $db->where("slug", $slug);
        else $db->where("category_id", "=", null);

        $uploads = $db->get();

        return view("cloud.index", compact(
            "slug",
            "uploads"
        ));
    }
}
