@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ isset($item) ? 'Edit Project' : 'Create New Project' }}</h1>
        <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Projects
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ isset($item) ? route('admin.projects.update', $item->id) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $item->title ?? '') }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug (optional)</label>
                    <input type="text" name="slug" value="{{ old('slug', $item->slug ?? '') }}" placeholder="auto-generated-if-empty" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $item->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- Technologies Selection Section -->
            <div class="mb-8 p-5 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50/50 dark:bg-gray-900/50">
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Technologies Used</label>
                <div class="flex flex-wrap gap-2 mb-4">
                    @php
                        $selectedTechIds = isset($item) ? $item->technologies->pluck('id')->toArray() : [];
                    @endphp
                    @foreach($allTechnologies as $tech)
                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium text-gray-700 dark:text-gray-300 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="checkbox" name="technologies[]" value="{{ $tech->id }}" {{ in_array($tech->id, old('technologies', $selectedTechIds)) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            {{ $tech->name }}
                        </label>
                    @endforeach
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Add New Technologies (comma-separated if not listed above)</label>
                    <input type="text" name="new_technologies" placeholder="e.g. Docker, Tailwind CSS, Redis" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <!-- PROMINENT MULTIPLE IMAGE UPLOAD SECTION -->
            <div class="mb-8 p-6 border-2 border-dashed border-indigo-500/50 rounded-xl bg-indigo-50/30 dark:bg-gray-900/60">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 rounded-lg bg-indigo-500/10 text-indigo-500">
                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <label for="project_images_input" class="block text-base font-bold text-gray-900 dark:text-white cursor-pointer hover:text-indigo-600 transition-colors">
                            Upload Project Screenshots / Images (Multiple Allowed)
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click button below. In the popup window, hold <kbd class="px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 rounded font-mono text-xs">Ctrl</kbd> to select multiple images.</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <input type="file" id="project_images_input" name="images[]" multiple="multiple" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif, image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 p-2" onchange="previewSelectedFiles(this)">
                </div>

                <!-- Live Selected File Names Preview -->
                <div id="file_preview_box" class="mt-3 hidden p-3 rounded-lg bg-indigo-100/50 dark:bg-gray-800 border border-indigo-200 dark:border-gray-700">
                    <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-400 block mb-1" id="file_count_badge">Selected 0 Files:</span>
                    <ul class="text-xs text-gray-600 dark:text-gray-300 space-y-1 list-disc pl-4" id="file_list_names"></ul>
                </div>
                
                @if(isset($item) && $item->images && $item->images->count() > 0)
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Current Uploaded Screenshots ({{ $item->images->count() }})</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($item->images as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="Project screenshot" class="h-28 w-full object-cover">
                                    <div class="p-2 flex justify-between items-center bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-xs text-gray-400 truncate">Image #{{ $loop->iteration }}</span>
                                        <button type="button" onclick="if(confirm('Delete this image?')) document.getElementById('delete-img-{{ $img->id }}').submit();" class="text-red-500 hover:text-red-700 p-1">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Short Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Short Description *</label>
                <textarea name="short_description" rows="2" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('short_description', $item->short_description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Problem -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Problem</label>
                    <textarea name="problem" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('problem', $item->problem ?? '') }}</textarea>
                </div>
                <!-- Solution -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Solution</label>
                    <textarea name="solution" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('solution', $item->solution ?? '') }}</textarea>
                </div>
                <!-- Architecture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Architecture (e.g. Frontend -> API -> Backend)</label>
                    <textarea name="architecture" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('architecture', $item->architecture ?? '') }}</textarea>
                </div>
                <!-- Challenges -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Challenges</label>
                    <textarea name="challenges" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('challenges', $item->challenges ?? '') }}</textarea>
                </div>
            </div>

            <!-- What I Learned -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">What I Learned</label>
                <textarea name="what_i_learned" rows="2" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('what_i_learned', $item->what_i_learned ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Live Demo URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live Demo URL</label>
                    <input type="url" name="live_demo_url" value="{{ old('live_demo_url', $item->live_demo_url ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <!-- GitHub URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GitHub URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $item->github_url ?? '') }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- Toggles -->
            <div class="flex items-center gap-6 mb-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $item->featured ?? false) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="featured" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Featured Project</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $item->is_published ?? true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_published" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Published</label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30">
                    {{ isset($item) ? 'Update Project' : 'Save Project' }}
                </button>
            </div>
        </form>

        @if(isset($item) && $item->images)
            @foreach($item->images as $img)
                <form id="delete-img-{{ $img->id }}" action="{{ route('admin.projects.images.destroy', $img->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>
</div>

<script>
function previewSelectedFiles(input) {
    const box = document.getElementById('file_preview_box');
    const badge = document.getElementById('file_count_badge');
    const list = document.getElementById('file_list_names');
    
    if (input.files && input.files.length > 0) {
        box.classList.remove('hidden');
        badge.textContent = `Selected ${input.files.length} Image(s) Ready to Upload:`;
        list.innerHTML = '';
        
        Array.from(input.files).forEach(file => {
            const li = document.createElement('li');
            li.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            list.appendChild(li);
        });
    } else {
        box.classList.add('hidden');
    }
}
</script>
@endsection