<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Previlige;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index($user_id)
    {
        // Fetch all menus with their permissions for the specific user
        $menus = Menu::with([
            'permissions' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }
        ])->get();

        return view('pages.privileges', compact('menus', 'user_id'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'menu' => 'nullable|array',
            'menu.*.can_view' => 'nullable|boolean',
            'menu.*.can_add' => 'nullable|boolean',
            'menu.*.can_edit' => 'nullable|boolean',
            'menu.*.can_delete' => 'nullable|boolean',
        ]);

        $userId = $data['user_id'];
        $menuPermissions = $data['menu'] ?? [];
        $submittedMenuIds = array_keys($menuPermissions);

        try {
            DB::transaction(function () use ($userId, $menuPermissions, $submittedMenuIds) {
                // Delete privileges for menus that were not submitted
                Previlige::where('user_id', $userId)
                    ->whereNotIn('menu_id', $submittedMenuIds)
                    ->delete();

                // Process submitted menus
                foreach ($menuPermissions as $menuId => $privileges) {
                    Previlige::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'menu_id' => $menuId,
                        ],
                        [
                            'can_view' => $privileges['can_view'] ?? 0,
                            'can_add' => $privileges['can_add'] ?? 0,
                            'can_edit' => $privileges['can_edit'] ?? 0,
                            'can_delete' => $privileges['can_delete'] ?? 0,
                        ]
                    );
                }
            });

            return redirect()->route('menus.privileges', ['user_id' => $userId])
                ->with('success', 'Privileges updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update privileges: ' . $e->getMessage());
        }
    }

}


