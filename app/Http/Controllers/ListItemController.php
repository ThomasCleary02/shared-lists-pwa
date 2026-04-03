<?php

namespace App\Http\Controllers;

use App\Models\ListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SharedList;

class ListItemController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SharedList $shared_list)
    {
        $this->ensureListOwner($shared_list);

        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $shared_list->items()->create([
            'content' => $validated['content'],
            'created_by' => Auth::id(),
            'is_complete' => false,
        ]);

        return redirect()->route('lists.show', $shared_list);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListItem $listItem)
    {
        $this->ensureListOwner($listItem->list);

        $validated = $request->validate([
            'content' => 'required|string|max:255',
            'is_complete' => 'required|boolean',
        ]);

        $listItem->update($validated);

        return redirect()->route('lists.show', $listItem->list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListItem $listItem)
    {

        $list = $listItem->list;
        
        $this->ensureListOwner($list);

        $listItem->delete();

        return redirect()->route('lists.show', $list);
    }

    private function ensureListOwner(SharedList $shared_list)
    {
        if ($shared_list->owner_id !== Auth::id()) {
            abort(403);
        }
    }
}
