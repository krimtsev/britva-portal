<?php

namespace App\Http\Controllers\Statements;

use App\Http\Controllers\Controller;
use App\Models\Statement\StatementCategory;

class StatementsCategoriesController extends Controller
{

    public function index()
    {
        $categories = StatementCategory::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.statements.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.statements.categories.create');
    }

    public function store(): \Illuminate\Http\RedirectResponse
    {
        $data = request()->validate([
            'title' => 'required|string',
        ]);

        StatementCategory::create($data);

        return redirect()->route('d.statements-categories.index');
    }

    public function edit(StatementCategory $category)
    {
        return view('dashboard.statements.categories.edit', compact('category'));
    }

    public function update(StatementCategory $category): \Illuminate\Http\RedirectResponse
    {
        $data = request()->validate([
            'title' => 'required|string',
        ]);

        $category->update($data);

        return redirect()->route('d.statements-categories.index');
    }
}
