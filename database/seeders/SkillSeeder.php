<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\SkillCategory;
use App\Models\Skill;
class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            'Programming' => ['Python', 'JavaScript', 'PHP'],
            'Backend' => ['Laravel', 'REST APIs', 'MySQL'],
            'AI' => ['LLM APIs', 'OpenAI API Integration', 'AI Automation', 'Prompt Engineering'],
            'Browser / Automation' => ['Chrome Extensions', 'Browser Automation'],
            'Tools' => ['Git', 'GitHub', 'VS Code']
        ];
        
        $catOrder = 1;
        foreach($cats as $catName => $skills) {
            $cat = SkillCategory::create(['name' => $catName, 'slug' => \Str::slug($catName), 'sort_order' => $catOrder++]);
            $skillOrder = 1;
            foreach($skills as $s) {
                Skill::create(['category_id' => $cat->id, 'name' => $s, 'slug' => \Str::slug($s), 'sort_order' => $skillOrder++, 'is_active' => true]);
            }
        }
    }
}
