<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitationModel extends Model
{
    protected $table = 'invitations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['household_id', 'email', 'token', 'invited_by_user_id', 'status', 'expires_at'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = '';
    
    /**
     * Create a new invitation
     */
    public function createInvitation($householdId, $email, $invitedByUserId, $expiresInDays = 7)
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiresInDays} days"));
        
        $invitationId = $this->insert([
            'household_id' => $householdId,
            'email' => strtolower(trim($email)),
            'token' => $token,
            'invited_by_user_id' => $invitedByUserId,
            'status' => 'pending',
            'expires_at' => $expiresAt
        ]);
        
        return ['token' => $token, 'invitation_id' => $invitationId];
    }
    
    /**
     * Get invitation by token with household and inviter details
     */
    public function getInvitationByToken($token)
    {
        return $this->select('invitations.*, households.name as household_name, users.name as inviter_name')
            ->join('households', 'invitations.household_id = households.id')
            ->join('users', 'invitations.invited_by_user_id = users.id')
            ->where('invitations.token', $token)
            ->first();
    }
    
    /**
     * Get pending invitations for a household
     */
    public function getPendingInvitations($householdId)
    {
        return $this->select('invitations.*, users.name as inviter_name')
            ->join('users', 'invitations.invited_by_user_id = users.id')
            ->where('invitations.household_id', $householdId)
            ->where('invitations.status', 'pending')
            ->where('invitations.expires_at >', date('Y-m-d H:i:s'))
            ->orderBy('invitations.created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Accept an invitation
     */
    public function acceptInvitation($token, $userId)
    {
        $invitation = $this->where('token', $token)->first();
        
        if (!$invitation) {
            return ['success' => false, 'message' => 'Invitation not found'];
        }
        
        if ($invitation['status'] !== 'pending') {
            return ['success' => false, 'message' => 'Invitation has already been used'];
        }
        
        if (strtotime($invitation['expires_at']) < time()) {
            return ['success' => false, 'message' => 'Invitation has expired'];
        }
        
        // Check if user is already a member
        $memberModel = new HouseholdMemberModel();
        if ($memberModel->isUserInHousehold($userId, $invitation['household_id'])) {
            return ['success' => false, 'message' => 'You are already a member of this household'];
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        // Add user to household
        $memberModel->insert([
            'household_id' => $invitation['household_id'],
            'user_id' => $userId,
            'role' => 'member'
        ]);
        
        // Update invitation status
        $this->update($invitation['id'], ['status' => 'accepted']);
        
        $db->transComplete();
        
        return ['success' => true, 'message' => 'Successfully joined household', 'household_id' => $invitation['household_id']];
    }
    
    /**
     * Cancel/delete an invitation
     */
    public function cancelInvitation($invitationId)
    {
        return $this->delete($invitationId);
    }
}
