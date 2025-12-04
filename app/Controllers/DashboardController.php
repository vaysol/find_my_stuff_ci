<?php

namespace App\Controllers;

use App\Models\HouseholdModel;

class DashboardController extends BaseController
{
    protected $householdModel;
    
    public function __construct()
    {
        $this->householdModel = new HouseholdModel();
        helper('household');
    }
    
    /**
     * Dashboard index
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $householdId = get_current_household();
        
        // If no household selected, set the first one
        if (!$householdId) {
            $households = get_user_households();
            
            if (empty($households)) {
                return redirect()->to('/onboarding');
            }
            
            set_current_household($households[0]['id']);
            $householdId = $households[0]['id'];
        }
        
        $household = $this->householdModel->find($householdId);
        $userHouseholds = get_user_households();
        $stats = get_household_stats($householdId);
        $userRole = get_user_role();
        
        return view('dashboard/index', [
            'household' => $household,
            'user_households' => $userHouseholds,
            'stats' => $stats,
            'user_role' => $userRole
        ]);
    }
}
