<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index() {
        $items = Project::with(['category', 'images', 'technologies'])->orderBy('sort_order')->get();
        return view("admin.projects.index", compact('items'));
    }

    public function create() {
        $categories = ProjectCategory::where('is_active', true)->orderBy('name')->get();
        $allTechnologies = Technology::orderBy('name')->get();
        return view("admin.projects.form", compact('categories', 'allTechnologies'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $data = $request->except(['images', 'technologies', 'new_technologies', 'cover_image_file']);
        $data['featured'] = $request->has('featured');
        $data['is_published'] = $request->has('is_published');
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image_file')) {
            $data['cover_image'] = $request->file('cover_image_file')->store('projects/covers', 'public');
        }

        $project = Project::create($data);

        // Sync Technologies
        $this->syncProjectTechnologies($project, $request);

        if ($request->hasFile('images')) {
            $firstImagePath = null;
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('projects', 'public');
                if ($index === 0) {
                    $firstImagePath = $path;
                }
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
            if (empty($project->cover_image) && $firstImagePath) {
                $project->update(['cover_image' => $firstImagePath]);
            }
        }

        return redirect()->route("admin.projects.index")->with('success', 'Project created successfully.');
    }

    public function edit($id) {
        $item = Project::with(['images', 'category', 'technologies'])->findOrFail($id);
        $categories = ProjectCategory::where('is_active', true)
            ->orWhere('id', $item->category_id)
            ->orderBy('name')
            ->get();
        $allTechnologies = Technology::orderBy('name')->get();
        return view("admin.projects.form", compact('item', 'categories', 'allTechnologies'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $project = Project::findOrFail($id);
        $data = $request->except(['images', 'technologies', 'new_technologies', 'cover_image_file']);
        $data['featured'] = $request->has('featured');
        $data['is_published'] = $request->has('is_published');
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image_file')) {
            if ($project->cover_image && !str_starts_with($project->cover_image, 'http')) {
                Storage::disk('public')->delete($project->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image_file')->store('projects/covers', 'public');
        }

        $project->update($data);

        // Sync Technologies
        $this->syncProjectTechnologies($project, $request);

        if ($request->hasFile('images')) {
            $firstImagePath = null;
            $existingCount = $project->images()->count();
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('projects', 'public');
                if ($index === 0) {
                    $firstImagePath = $path;
                }
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $existingCount + $index,
                ]);
            }
            if (empty($project->cover_image) && $firstImagePath) {
                $project->update(['cover_image' => $firstImagePath]);
            }
        }

        return redirect()->route("admin.projects.index")->with('success', 'Project updated successfully.');
    }

    protected function syncProjectTechnologies(Project $project, Request $request) {
        $techIds = $request->input('technologies', []);

        if ($request->filled('new_technologies')) {
            $names = array_filter(array_map('trim', explode(',', $request->input('new_technologies'))));
            foreach ($names as $name) {
                $tech = Technology::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $techIds[] = $tech->id;
            }
        }

        $project->technologies()->sync(array_unique($techIds));
    }

    public function destroy($id) {
        $project = Project::with('images')->findOrFail($id);
        foreach ($project->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        if ($project->cover_image) {
            Storage::disk('public')->delete($project->cover_image);
        }
        $project->delete();
        return redirect()->route("admin.projects.index")->with('success', 'Project deleted successfully.');
    }

    public function destroyImage($id) {
        $image = ProjectImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
