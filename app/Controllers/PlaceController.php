<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\HouseholdModel;

class PlaceController extends BaseController
{
    protected $placeModel;
    protected $householdModel;
    
    public function __construct()
    {
        $this->placeModel = new PlaceModel();
        $this->householdModel = new HouseholdModel();
        helper('household');
    }
    
    /**
     * List all places
     */
    public function index()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $places = $this->placeModel->getHouseholdPlaces($householdId);
        $household = $this->householdModel->find($householdId);
        
        return view('place/index', [
            'places' => $places,
            'household' => $household
        ]);
    }
    
    /**
     * Show create place form
     */
    public function create()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $household = $this->householdModel->find($householdId);
        
        return view('place/create', ['household' => $household]);
    }
    
    /**
     * Store new place
     */
    public function store()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $name = $this->request->getPost('name');
        $room = $this->request->getPost('room');
        $notes = $this->request->getPost('notes');
        
        if (empty($name)) {
            session()->setFlashdata('error', 'Place name is required!');
            return redirect()->back()->withInput();
        }
        
        $this->placeModel->createPlace($householdId, $name, $room, $notes);
        
        session()->setFlashdata('success', "Place \"{$name}\" created successfully!");
        return redirect()->to('/places');
    }
    
    /**
     * Show edit place form
     */
    public function edit($placeId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $place = $this->placeModel->getPlace($placeId, $householdId);
        
        if (!$place) {
            session()->setFlashdata('error', 'Place not found!');
            return redirect()->to('/places');
        }
        
        $household = $this->householdModel->find($householdId);
        
        return view('place/edit', [
            'place' => $place,
            'household' => $household
        ]);
    }
    
    /**
     * Update place
     */
    public function update($placeId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $name = $this->request->getPost('name');
        $room = $this->request->getPost('room');
        $notes = $this->request->getPost('notes');
        
        if (empty($name)) {
            session()->setFlashdata('error', 'Place name is required!');
            return redirect()->back()->withInput();
        }
        
        $this->placeModel->updatePlace($placeId, $householdId, $name, $room, $notes);
        
        session()->setFlashdata('success', "Place \"{$name}\" updated successfully!");
        return redirect()->to('/places');
    }
    
    /**
     * Delete place
     */
    public function delete($placeId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $place = $this->placeModel->getPlace($placeId, $householdId);
        
        if (!$place) {
            session()->setFlashdata('error', 'Place not found!');
            return redirect()->to('/places');
        }
        
        $result = $this->placeModel->deletePlace($placeId, $householdId);
        
        session()->setFlashdata($result['success'] ? 'success' : 'error', $result['message']);
        return redirect()->to('/places');
    }
}
