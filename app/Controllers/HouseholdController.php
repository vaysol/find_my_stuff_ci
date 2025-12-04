<?php

namespace App\Controllers;

use App\Models\HouseholdModel;
use App\Models\HouseholdMemberModel;
use App\Models\InvitationModel;

class HouseholdController extends BaseController
{
    protected $householdModel;
    protected $memberModel;
    protected $invitationModel;
    
    public function __construct()
    {
        $this->householdModel = new HouseholdModel();
        $this->memberModel = new HouseholdMemberModel();
        $this->invitationModel = new InvitationModel();
        helper('household');
    }
    
    /**
     * Create new household
     */
    public function create()
    {
        $userId = session()->get('user_id');
        
        if ($this->request->getMethod() === 'post') {
            $name = $this->request->getPost('name');
            
            if (empty($name)) {
                session()->setFlashdata('error', 'Household name is required!');
                return redirect()->back();
            }
            
            $householdId = $this->householdModel->createHousehold($name, $userId);
            set_current_household($householdId);
            
            session()->setFlashdata('success', "Household \"{$name}\" created successfully!");
            return redirect()->to('/');
        }
        
        return view('household/create');
    }
    
    /**
     * Switch to different household
     */
    public function switch($householdId)
    {
        if (!check_household_access($householdId)) {
            session()->setFlashdata('error', 'You do not have access to this household!');
            return redirect()->to('/');
        }
        
        set_current_household($householdId);
        $household = $this->householdModel->find($householdId);
        
        session()->setFlashdata('success', "Switched to household: {$household['name']}");
        return redirect()->to('/');
    }
    
    /**
     * Household settings
     */
    public function settings()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $userId = session()->get('user_id');
        $household = $this->householdModel->find($householdId);
        $userRole = get_user_role();
        
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');
            
            if ($action === 'update_name') {
                $newName = $this->request->getPost('name');
                
                if (!empty($newName)) {
                    $this->householdModel->updateName($householdId, $newName);
                    session()->setFlashdata('success', 'Household name updated!');
                }
                
                return redirect()->to('/household/settings');
            }
            
            if ($action === 'delete' && $userRole === 'owner') {
                $this->householdModel->delete($householdId);
                session()->remove('current_household_id');
                
                // Set another household if user has any
                $households = get_user_households();
                
                if (!empty($households)) {
                    set_current_household($households[0]['id']);
                }
                
                session()->setFlashdata('success', 'Household deleted successfully!');
                return redirect()->to('/');
            }
        }
        
        $members = $this->memberModel->getHouseholdMembers($householdId);
        $pendingInvitations = $this->invitationModel->getPendingInvitations($householdId);
        
        return view('household/settings', [
            'household' => $household,
            'members' => $members,
            'pending_invitations' => $pendingInvitations,
            'user_role' => $userRole
        ]);
    }
    
    /**
     * Invite member
     */
    public function invite()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $userRole = get_user_role();
        
        if (!in_array($userRole, ['owner', 'admin'])) {
            session()->setFlashdata('error', 'You do not have permission to invite members!');
            return redirect()->to('/household/settings');
        }
        
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Valid email is required!');
                return redirect()->back();
            }
            
            $userId = session()->get('user_id');
            $result = $this->invitationModel->createInvitation($householdId, $email, $userId);
            
            $invitationLink = base_url("/invite/accept/{$result['token']}");
            
            session()->setFlashdata('success', "Invitation created! Share this link: {$invitationLink}");
            return redirect()->to('/household/settings');
        }
        
        $household = $this->householdModel->find($householdId);
        
        return view('household/invite', ['household' => $household]);
    }
    
    /**
     * Accept invitation
     */
    public function acceptInvite($token)
    {
        $invitation = $this->invitationModel->getInvitationByToken($token);
        
        if (!$invitation) {
            session()->setFlashdata('error', 'Invalid invitation link!');
            return redirect()->to('/landing');
        }
        
        if ($invitation['status'] !== 'pending') {
            session()->setFlashdata('error', 'This invitation has already been used!');
            return redirect()->to('/landing');
        }
        
        if ($this->request->getMethod() === 'post') {
            $userId = session()->get('user_id');
            
            if (!$userId) {
                session()->setFlashdata('info', 'Please log in or register to accept this invitation.');
                session()->set('pending_invitation_token', $token);
                return redirect()->to('/login');
            }
            
            $result = $this->invitationModel->acceptInvitation($token, $userId);
            
            if ($result['success']) {
                set_current_household($result['household_id']);
                session()->setFlashdata('success', $result['message']);
                return redirect()->to('/');
            } else {
                session()->setFlashdata('error', $result['message']);
                return redirect()->to('/');
            }
        }
        
        return view('household/accept_invite', ['invitation' => $invitation]);
    }
    
    /**
     * Remove member from household
     */
    public function removeMember($userId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $userRole = get_user_role();
        
        if (!in_array($userRole, ['owner', 'admin'])) {
            session()->setFlashdata('error', 'You do not have permission to remove members!');
            return redirect()->to('/household/settings');
        }
        
        $result = $this->memberModel->removeMember($householdId, $userId);
        
        session()->setFlashdata($result['success'] ? 'success' : 'error', $result['message']);
        return redirect()->to('/household/settings');
    }
}
