<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        $query = Club::with('events', 'users');

        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->filled('events_min')) {
            $query->has('events', '>=', $request->events_min);
        }

        if ($request->filled('events_max')) {
            $query->has('events', '<=', $request->events_max);
        }

        if ($request->filled('members_min')) {
            $query->has('users', '>=', $request->members_min);
        }

        if ($request->filled('members_max')) {
            $query->has('users', '<=', $request->members_max);
        }

        if ($request->filled('created_from')) {
            $query->where('created_at', '>=', $request->created_from . ' 00:00:00');
        }

        if ($request->filled('created_to')) {
            $query->where('created_at', '<=', $request->created_to . ' 23:59:59');
        }

        $clubs = $query->orderBy('created_at', 'desc')->get();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Club::create($request->all());

        return redirect()->route('clubs.index')->with('success', 'Club created successfully.');
    }

    public function show(Club $club)
    {
        return view('clubs.show', compact('club'));
    }

    public function edit(Club $club)
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $club->update($request->all());

        return redirect()->route('clubs.index')->with('success', 'Club updated successfully.');
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully.');
    }
}