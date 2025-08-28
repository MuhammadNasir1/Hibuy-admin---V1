<?php

if (!function_exists('canMenuAction')) {
    /**
     * Check if user has permission for a specific menu and action
     *
     * @param string $slug  The menu slug (e.g., 'products')
     * @param string $action The action type: view, add, edit, delete
     * @return bool
     */
    function canMenuAction($slug, $action )
    {
        $privileges = session('user_details.user_privileges') ?? [];

        $actionKey = 'can_' . strtolower($action); // e.g., 'can_view'

        foreach ($privileges as $priv) {
            if ($priv['menu_slug'] === $slug && isset($priv[$actionKey]) && $priv[$actionKey] == 1) {
                return true;
            }
        }

        return false;
    }
}
