<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ProjectCategory;
use App\Models\Project;
use App\Models\Technology;
use App\Models\ProjectFeature;
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $cats = ['AI', 'Backend', 'Chrome Extension', 'Web Development'];
        foreach($cats as $c) {
            ProjectCategory::create(['name' => $c, 'slug' => \Str::slug($c)]);
        }
        
        $techs = ['Laravel', 'PHP', 'REST API', 'AI APIs', 'Chrome Extension', 'MySQL', 'JavaScript', 'HTML', 'CSS', 'Automation', 'REST APIs'];
        foreach(array_unique($techs) as $t) {
            Technology::firstOrCreate(['name' => $t, 'slug' => \Str::slug($t)]);
        }

        // Proj 1
        $cat = ProjectCategory::where('name', 'AI')->first();
        $p1 = Project::create([
            'category_id' => $cat->id,
            'title' => 'AI Job Assistant',
            'slug' => 'ai-job-assistant',
            'short_description' => 'An AI-powered job assistance platform/Chrome extension designed to help analyze job opportunities and assist with job applications.',
            'problem' => 'Describe only the general problem implied by the provided information.',
            'solution' => 'A platform/extension combining backend APIs, AI functionality, and a browser-extension workflow.',
            'architecture' => 'Chrome Extension -> REST API -> Laravel Backend -> MySQL -> AI APIs',
            'featured' => true,
            'is_published' => true,
            'sort_order' => 1
        ]);
        
        foreach(['Job analysis', 'Resume/profile-based assistance', 'AI integration', 'Backend API', 'Chrome extension workflow'] as $f) {
            ProjectFeature::create(['project_id' => $p1->id, 'feature' => $f, 'sort_order' => 1]);
        }
        
        $p1->technologies()->attach(Technology::whereIn('name', ['Laravel', 'PHP', 'REST API', 'AI APIs', 'Chrome Extension', 'MySQL'])->pluck('id'));

        // Proj 2
        $cat2 = ProjectCategory::where('name', 'Backend')->first();
        $p2 = Project::create([
            'category_id' => $cat2->id,
            'title' => 'Dynamic AI Provider Management Backend',
            'slug' => 'dynamic-ai-provider-management-backend',
            'short_description' => 'A backend system for managing multiple AI providers and API keys securely.',
            'architecture' => 'Client -> REST API -> Laravel Backend -> AI Provider Management -> MySQL',
            'featured' => true,
            'is_published' => true,
            'sort_order' => 2
        ]);
        foreach(['AI provider management', 'Encrypted API key storage', 'Configurable default provider', 'Fallback provider support', 'Usage tracking', 'Token/usage management', 'API-based architecture'] as $f) {
            ProjectFeature::create(['project_id' => $p2->id, 'feature' => $f, 'sort_order' => 1]);
        }
        $p2->technologies()->attach(Technology::whereIn('name', ['Laravel', 'PHP', 'MySQL', 'REST API', 'AI APIs'])->pluck('id'));

        // Proj 3
        $cat3 = ProjectCategory::where('name', 'Chrome Extension')->first();
        $p3 = Project::create([
            'category_id' => $cat3->id,
            'title' => 'Chrome Extension Projects',
            'slug' => 'chrome-extension-projects',
            'short_description' => 'Browser-extension development focused on practical workflows, API integration, automation, and user-friendly extension interfaces.',
            'featured' => false,
            'is_published' => true,
            'sort_order' => 3
        ]);
        foreach(['Chrome Extension architecture', 'API integration', 'Browser automation', 'User-friendly extension interfaces'] as $f) {
            ProjectFeature::create(['project_id' => $p3->id, 'feature' => $f, 'sort_order' => 1]);
        }
        $p3->technologies()->attach(Technology::whereIn('name', ['JavaScript', 'Chrome Extension', 'REST API', 'Automation'])->pluck('id'));

        // Proj 4
        $cat4 = ProjectCategory::where('name', 'Web Development')->first();
        $p4 = Project::create([
            'category_id' => $cat4->id,
            'title' => 'Website Development Projects',
            'slug' => 'website-development-projects',
            'short_description' => 'Professional website development work focused on clean interfaces, responsive layouts, and practical business-oriented web experiences.',
            'featured' => false,
            'is_published' => true,
            'sort_order' => 4
        ]);
        $p4->technologies()->attach(Technology::whereIn('name', ['HTML', 'CSS', 'JavaScript'])->pluck('id'));

    }
}
