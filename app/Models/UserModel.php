<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['email', 'password_hash', 'name', 'last_login', 'created_at'];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = '';
    
    // Validation
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email]',
        'name' => 'required|min_length[2]',
        'password_hash' => 'required'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ]
    ];
    
    /**
     * Create a new user with hashed password
     */
    public function createUser($email, $password, $name)
    {
        $data = [
            'email' => strtolower(trim($email)),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'name' => trim($name),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', strtolower(trim($email)))->first();
    }
    
    /**
     * Verify user password
     */
    public function verifyPassword($user, $password)
    {
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password_hash']);
    }
    
    /**
     * Update user's last login timestamp
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
}
