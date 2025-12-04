<?php

namespace App\Models;

use CodeIgniter\Model;

class HouseholdMemberModel extends Model
{
    protected $table = 'household_members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['household_id', 'user_id', 'role', 'joined_at'];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'joined_at';
    protected $updatedField = null;
    protected $deletedField = '';
    
    // Validation
    protected $validationRules = [
        'household_id' => 'required|integer',
        'user_id' => 'required|integer',
        'role' => 'required|in_list[owner,admin,member]'
    ];
    
    /**
     * Get all members of a household with user details
     */
    public function getHouseholdMembers($householdId)
    {
        return $this->select('household_members.*, users.email, users.name')
            ->join('users', 'household_members.user_id = users.id')
            ->where('household_members.household_id', $householdId)
            ->orderBy('CASE household_members.role 
                WHEN "owner" THEN 1 
                WHEN "admin" THEN 2 
                WHEN "member" THEN 3 
                END')
            ->orderBy('household_members.joined_at')
            ->findAll();
    }
    
    /**
     * Get user's role in a household
     */
    public function getUserRole($userId, $householdId)
    {
        $member = $this->where([
            'user_id' => $userId,
            'household_id' => $householdId
        ])->first();
        
        return $member ? $member['role'] : null;
    }
    
    /**
     * Check if user is in household
     */
    public function isUserInHousehold($userId, $householdId)
    {
        return $this->getUserRole($userId, $householdId) !== null;
    }
    
    /**
     * Remove a member from household
     */
    public function removeMember($householdId, $userId)
    {
        // Don't remove if user is owner
        $role = $this->getUserRole($userId, $householdId);
        
        if ($role === 'owner') {
            return ['success' => false, 'message' => 'Cannot remove the household owner'];
        }
        
        $this->where([
            'household_id' => $householdId,
            'user_id' => $userId
        ])->delete();
        
        return ['success' => true, 'message' => 'Member removed successfully'];
    }
    
    /**
     * Update member role
     */
    public function updateRole($householdId, $userId, $newRole)
    {
        return $this->where([
            'household_id' => $householdId,
            'user_id' => $userId
        ])->set(['role' => $newRole])->update();
    }
}
