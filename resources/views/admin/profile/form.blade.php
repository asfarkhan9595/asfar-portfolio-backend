@extends('layouts.admin')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i data-lucide="edit-3" class="w-7 h-7 text-indigo-600 dark:text-indigo-400"></i>
                Edit Profile
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your personal information, roles, and bio.</p>
        </div>
        <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-sm font-medium rounded-lg transition-colors border border-gray-200 dark:border-gray-600">
            <i data-lucide="eye" class="w-4 h-4 mr-2"></i> View Profile
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Font & Text Tool Header -->
    <div class="mb-6 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-emerald-500/10 rounded-xl border border-indigo-200 dark:border-indigo-500/20 p-4 backdrop-blur-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="p-2 rounded-lg bg-indigo-600 text-white shadow-sm">
                    <i data-lucide="type" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Font & Text Formatting Tool</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Automatically normalizes fancy fonts, pasted text & quotes to website default (Inter)</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="cleanAllFormFields()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow transition-colors">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Clean & Normalize All Fonts
                </button>
                <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300 cursor-pointer px-2.5 py-1 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" id="autoNormalizeCheck" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>Auto-Clean on Save</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                    <input type="text" id="input_name" name="name" value="{{ $item->name ?? '' }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                </div>
                
                <!-- Primary Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Role</label>
                    <input type="text" id="input_primary_role" name="primary_role" value="{{ $item->primary_role ?? '' }}" required class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <!-- Secondary Roles -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secondary Roles</label>
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Separate multiple roles with commas (e.g., Backend Developer, API Developer)</div>
                <input type="text" id="input_secondary_roles" name="secondary_roles" value="{{ $item->secondary_roles_string ?? '' }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Hero Supporting Text -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hero Supporting Text (Tagline)</label>
                    <button type="button" onclick="cleanField('input_hero')" class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <i data-lucide="wand-2" class="w-3 h-3"></i> Normalize Font
                    </button>
                </div>
                <textarea id="input_hero" name="hero_supporting_text" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">{{ $item->hero_supporting_text ?? '' }}</textarea>
            </div>

            <!-- About Text -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">About Me (Bio)</label>
                    <button type="button" onclick="cleanField('input_about')" class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <i data-lucide="wand-2" class="w-3 h-3"></i> Normalize Font
                    </button>
                </div>
                <textarea id="input_about" name="about" rows="4" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">{{ $item->about ?? '' }}</textarea>
            </div>

            <!-- Profile Image URL -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profile Image URL</label>
                <input type="text" id="input_profile_image" name="profile_image" value="{{ $item->profile_image ?? '' }}" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white" placeholder="https://example.com/image.jpg">
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="cleanAllFormFields()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center gap-1.5">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Reset to Default Font
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function normalizeFancyText(str) {
        if (!str) return '';
        // NFKC unicode normalization converts mathematical bold/italic/script unicode font characters to standard Latin
        let clean = str.normalize('NFKC');
        
        // Clean smart quotes and dashes
        clean = clean
            .replace(/[\u201C\u201D\u201E\u201F\u275D\u275E]/g, '"')
            .replace(/[\u2018\u2019\u201A\u201B\u275B\u275C]/g, "'")
            .replace(/[\u2013\u2014\u2015]/g, '-')
            .replace(/\u2026/g, '...');
            
        return clean;
    }

    function cleanField(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) {
            el.value = normalizeFancyText(el.value);
        }
    }

    function cleanAllFormFields() {
        const fields = ['input_name', 'input_primary_role', 'input_secondary_roles', 'input_hero', 'input_about'];
        fields.forEach(id => cleanField(id));
    }

    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        const autoClean = document.getElementById('autoNormalizeCheck')?.checked;
        if (autoClean) {
            cleanAllFormFields();
        }
    });

    // Auto clean on paste event
    ['input_hero', 'input_about', 'input_name', 'input_primary_role'].forEach(id => {
        document.getElementById(id)?.addEventListener('paste', function(e) {
            setTimeout(() => cleanField(id), 10);
        });
    });
</script>
@endsection