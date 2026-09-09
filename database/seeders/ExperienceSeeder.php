<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Experience;
class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        Experience::create([
            'title' => 'Independent Projects & Development',
            'description' => 'Asfar has been building practical projects involving AI integrations, backend APIs, Chrome extensions, automation, and websites.',
            'is_published' => true
        ]);
    }
}
