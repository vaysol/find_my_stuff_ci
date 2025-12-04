<?php

if (!function_exists('get_current_household')) {
    /**
     * Get current household ID from session
     */
    function get_current_household()
    {
        return session()->get('current_household_id');
    }
}

if (!function_exists('set_current_household')) {
    /**
     * Set current household ID in session
     */
    function set_current_household($householdId)
    {
        session()->set('current_household_id', $householdId);
    }
}

if (!function_exists('get_current_user')) {
    /**
     * Get current user from session
     */
    function get_current_user()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return null;
        }
        
        $userModel = new \App\Models\UserModel();
        return $userModel->find($userId);
    }
}

if (!function_exists('check_household_access')) {
    /**
     * Check if current user has access to household
     */
    function check_household_access($householdId)
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return false;
        }
        
        $memberModel = new \App\Models\HouseholdMemberModel();
        return $memberModel->isUserInHousehold($userId, $householdId);
    }
}

if (!function_exists('get_user_role')) {
    /**
     * Get current user's role in current household
     */
    function get_user_role()
    {
        $userId = session()->get('user_id');
        $householdId = get_current_household();
        
        if (!$userId || !$householdId) {
            return null;
        }
        
        $memberModel = new \App\Models\HouseholdMemberModel();
        return $memberModel->getUserRole($userId, $householdId);
    }
}

if (!function_exists('get_user_households')) {
    /**
     * Get all households for current user
     */
    function get_user_households()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return [];
        }
        
        $householdModel = new \App\Models\HouseholdModel();
        return $householdModel->getUserHouseholds($userId);
    }
}

if (!function_exists('get_household_stats')) {
    /**
     * Get statistics for a household
     */
    function get_household_stats($householdId)
    {
        $db = \Config\Database::connect();
        
        // Total items
        $totalItems = $db->table('items')
            ->where('household_id', $householdId)
            ->countAllResults();
        
        // Total places
        $totalPlaces = $db->table('places')
            ->where('household_id', $householdId)
            ->countAllResults();
        
        // Recent items
        $itemModel = new \App\Models\ItemModel();
        $recentItems = $itemModel->select('items.*, places.name as assigned_place_name')
            ->join('places', 'items.assigned_place_id = places.id', 'left')
            ->where('items.household_id', $householdId)
            ->orderBy('items.last_updated', 'DESC')
            ->limit(10)
            ->findAll();
        
        // Categories
        $categories = $itemModel->getCategories($householdId);
        
        return [
            'total_items' => $totalItems,
            'total_places' => $totalPlaces,
            'recent_items' => $recentItems,
            'categories' => array_column($categories, 'category')
        ];
    }
}
