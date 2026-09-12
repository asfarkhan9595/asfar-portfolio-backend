<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Profile extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'primary_role', 'secondary_roles', 'hero_supporting_text', 'about', 'profile_image', 'show_profile_image'];
    protected $casts = [
        'secondary_roles' => 'array',
        'show_profile_image' => 'boolean',
    ];
}
