<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    public function index(Request $request) {
        $query = Skill::with('category');

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort order
        $sort = $request->input('sort', 'sort_order_asc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'sort_order_desc':
                $query->orderBy('sort_order', 'desc');
                break;
            case 'sort_order_asc':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
                break;
        }

        $perPage = (int) $request->input('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $items = $query->paginate($perPage)->withQueryString();
        $categories = SkillCategory::orderBy('name')->get();

        return view("admin.skills.index", compact('items', 'categories', 'perPage'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:skill_categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        Skill::create([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:skill_categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        $skill = Skill::findOrFail($id);
        $skill->update([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function toggleActive($id) {
        $skill = Skill::findOrFail($id);
        $skill->update(['is_active' => !$skill->is_active]);

        $statusText = $skill->is_active ? 'Active' : 'Inactive';
        return redirect()->back()->with('success', "'{$skill->name}' is now {$statusText}.");
    }

    public function destroy($id) {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }
}
