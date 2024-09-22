<?php

namespace App\Http\Controllers\UploadCategories;

use App\Http\Controllers\Controller;
use App\Models\UploadCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UploadCategoriesController extends Controller
{
    public function index()
    {
        $categories = UploadCategories::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.upload-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.upload-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|alpha_dash|unique:upload_categories|max:255',
        ]);

        UploadCategories::create($validated);

        return redirect()->route('d.upload-categories.index');
    }

    public function edit(UploadCategories $category)
    {
        return view('dashboard.upload-categories.edit', compact('category'));
    }

    public function update(Request $request, UploadCategories $category)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|alpha_dash|max:255|unique:sheets,slug,'.$category->id,
        ]);

        $category->fill($validated);

        $category->save();

        return redirect()->route('d.upload-categories.index');
    }
}
