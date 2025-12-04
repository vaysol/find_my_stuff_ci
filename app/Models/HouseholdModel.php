<?php

namespace App\Models;

use CodeIgniter\Model;

class HouseholdModel extends Model
{
    protected $table = 'households';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'created_by_user_id', 'created_at'];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = '';
    
    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]',
        'created_by_user_id' => 'required|integer'
    ];
    
    /**
     * Create a new household and add creator as owner
     */
    public function createHousehold($name, $userId)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Create household
        $householdId = $this->insert([
            'name' => trim($name),
            'created_by_user_id' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Add creator as owner
        $memberModel = new HouseholdMemberModel();
        $memberModel->insert([
            'household_id' => $householdId,
            'user_id' => $userId,
            'role' => 'owner',
            'joined_at' => date('Y-m-d H:i:s')
        ]);
        
        $db->transComplete();
        
        return $householdId;
    }
    
    /**
     * Get all households for a user with their role
     */
    public function getUserHouseholds($userId)
    {
        return $this->select('households.*, household_members.role')
            ->join('household_members', 'households.id = household_members.household_id')
            ->where('household_members.user_id', $userId)
            ->orderBy('households.created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Update household name
     */
    public function updateName($householdId, $name)
    {
        return $this->update($householdId, ['name' => trim($name)]);
    }
    
    /**
     * Get household by ID
     */
    public function getHousehold($householdId)
    {
        return $this->find($householdId);
    }
}
