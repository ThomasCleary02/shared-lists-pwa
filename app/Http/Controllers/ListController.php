<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\SharedList;

class ListController extends Controller
{
    public function index()
    {
        $shared_lists = Auth::user()->listsOwned()->get();
        return Inertia::render('Lists/Index', [
            'shared_lists' => $shared_lists,
        ]);
    }

    public function create()
    {
        return Inertia::render('Lists/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Auth::user()->listsOwned()->create($validated);
        return redirect()->route('lists.index');
    }

    public function show(SharedList $shared_list)
    {
        if ($shared_list->owner_id !== Auth::id()) {
            abort(403);
        }

        $shared_list->load([
            'items' => fn ($query) => $query->orderBy('id')
        ]);

        return Inertia::render('Lists/Show', [
            'shared_list' => $shared_list
        ]);
    }

    public function destroy(SharedList $shared_list)
    {

        if ($shared_list->owner_id !== Auth::id()) {
            abort(403);
        }

        $shared_list->delete();
        return redirect()->route('lists.index');
    }
}
