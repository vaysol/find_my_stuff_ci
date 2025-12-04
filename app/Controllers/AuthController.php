<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HouseholdModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $householdModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->householdModel = new HouseholdModel();
        helper('household');
    }
    
    /**
     * Landing page
     */
    public function landing()
    {
        // Redirect if already logged in
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        
        return view('auth/landing');
    }
    
    /**
     * Login page and handler
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $remember = $this->request->getPost('remember');
            
            // Validation
            if (empty($email) || empty($password)) {
                session()->setFlashdata('error', 'Email and password are required!');
                return redirect()->back()->withInput();
            }
            
            // Get user and verify password
            $user = $this->userModel->getUserByEmail($email);
            
            if ($user && $this->userModel->verifyPassword($user, $password)) {
                // Login successful
                session()->set([
                    'user_id' => $user['id'],
                    'user_email' => $user['email'],
                    'user_name' => $user['name']
                ]);
                
                // Update last login
                $this->userModel->updateLastLogin($user['id']);
                
                // Check if user has households
                $households = $this->householdModel->getUserHouseholds($user['id']);
                
                if (empty($households)) {
                    // New user - redirect to onboarding
                    return redirect()->to('/onboarding');
                }
                
                // Set first household as current
                set_current_household($households[0]['id']);
                
                // Redirect to next page or dashboard
                $next = $this->request->getGet('next');
                return redirect()->to($next ?: '/');
            } else {
                session()->setFlashdata('error', 'Invalid email or password!');
                return redirect()->back()->withInput();
            }
        }
        
        return view('auth/login');
    }
    
    /**
     * Register page and handler
     */
    public function register()
    {
        // Redirect if already logged in
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        
        log_message('error', 'Register method called. Method: ' . $this->request->getMethod());
        
        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('error', 'Registration attempt started');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');
            $name = $this->request->getPost('name');
            
            // Validation
            $errors = [];
            
            if (empty($email) || empty($password) || empty($confirmPassword) || empty($name)) {
                $errors[] = 'All fields are required!';
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email address!';
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match!';
            }
            
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters long!';
            }
            
            if (!empty($errors)) {
                log_message('error', 'Registration validation errors: ' . implode(', ', $errors));
                session()->setFlashdata('error', implode(' ', $errors));
                return redirect()->back()->withInput();
            }
            
            // Create user
            $userId = $this->userModel->createUser($email, $password, $name);
            
            if ($userId === false) {
                log_message('error', 'User creation failed for email: ' . $email);
                session()->setFlashdata('error', 'Email already registered!');
                return redirect()->back()->withInput();
            }
            
            log_message('error', 'User created successfully: ' . $userId);
            
            session()->setFlashdata('success', 'Registration successful! Please log in.');
            return redirect()->to('/login');
        }
        
        return view('auth/register');
    }
    
    /**
     * Onboarding - create first household
     */
    public function onboarding()
    {
        // Must be logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        
        // Check if user already has households
        $households = $this->householdModel->getUserHouseholds($userId);
        if (!empty($households)) {
            return redirect()->to('/');
        }
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $householdName = $this->request->getPost('household_name');
            
            if (empty($householdName)) {
                session()->setFlashdata('error', 'Household name is required!');
                return redirect()->back();
            }
            
            // Create household
            $householdId = $this->householdModel->createHousehold($householdName, $userId);
            set_current_household($householdId);
            
            session()->setFlashdata('success', "Welcome! Household \"{$householdName}\" created successfully!");
            return redirect()->to('/');
        }
        
        return view('auth/onboarding');
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('info', 'You have been logged out.');
        return redirect()->to('/landing');
    }
}
