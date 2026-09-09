<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show() {
        $item = Profile::first() ?? new Profile();
        return view("admin.profile.show", compact('item'));
    }

    public function edit() {
        $item = Profile::first() ?? new Profile();
        if (is_array($item->secondary_roles)) {
            $item->secondary_roles_string = implode(', ', $item->secondary_roles);
        } else {
            $item->secondary_roles_string = '';
        }
        return view("admin.profile.form", compact('item'));
    }

    public function update(Request $request) {
        $item = Profile::first() ?? new Profile();
        $data = $request->all();
        
        if (isset($data['secondary_roles'])) {
            $roles = array_filter(array_map('trim', explode(',', $data['secondary_roles'])));
            $data['secondary_roles'] = array_values($roles);
        }

        $item->fill($data);
        $item->save();
        return redirect()->route("admin.profile.show")->with('success', 'Profile updated successfully.');
    }
}
