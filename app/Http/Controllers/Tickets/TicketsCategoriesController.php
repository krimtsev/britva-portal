<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Ticket\TicketCategory;

class TicketsCategoriesController extends Controller
{

    public function index()
    {
        $categories = TicketCategory::orderBy('id', 'ASC')->paginate(30);

        return view('dashboard.tickets.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.tickets.categories.create');
    }

    public function store(): \Illuminate\Http\RedirectResponse
    {
        $data = request()->validate([
            'title' => 'required|string',
        ]);

        TicketCategory::create($data);

        return redirect()->route('d.tickets-categories.index');
    }

    public function edit(TicketCategory $category)
    {
        return view('dashboard.tickets.categories.edit', compact('category'));
    }

    public function update(TicketCategory $category): \Illuminate\Http\RedirectResponse
    {
        $data = request()->validate([
            'title' => 'required|string',
        ]);

        $category->update($data);

        return redirect()->route('d.tickets-categories.index');
    }
}
