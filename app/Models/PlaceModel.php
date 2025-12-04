<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaceModel extends Model
{
    protected $table = 'places';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['household_id', 'name', 'room', 'notes'];
    
    // Validation
    protected $validationRules = [
        'household_id' => 'required|integer',
        'name' => 'required|min_length[2]'
    ];
    
    /**
     * Get all places for a household with item counts
     */
    public function getHouseholdPlaces($householdId)
    {
        return $this->select('places.*, COUNT(items.id) as item_count')
            ->join('items', 'places.id = items.assigned_place_id AND items.household_id = places.household_id', 'left')
            ->where('places.household_id', $householdId)
            ->groupBy('places.id')
            ->orderBy('places.name')
            ->findAll();
    }
    
    /**
     * Get place by ID within household context
     */
    public function getPlace($placeId, $householdId)
    {
        return $this->where('id', $placeId)
            ->where('household_id', $householdId)
            ->first();
    }
    
    /**
     * Create a new place
     */
    public function createPlace($householdId, $name, $room, $notes)
    {
        return $this->insert([
            'household_id' => $householdId,
            'name' => trim($name),
            'room' => trim($room),
            'notes' => trim($notes)
        ]);
    }
    
    /**
     * Update an existing place
     */
    public function updatePlace($placeId, $householdId, $name, $room, $notes)
    {
        return $this->where('id', $placeId)
            ->where('household_id', $householdId)
            ->set([
                'name' => trim($name),
                'room' => trim($room),
                'notes' => trim($notes)
            ])
            ->update();
    }
    
    /**
     * Delete a place if it has no assigned items
     */
    public function deletePlace($placeId, $householdId)
    {
        $db = \Config\Database::connect();
        
        // Check if any items are assigned to this place
        $itemCount = $db->table('items')
            ->where('assigned_place_id', $placeId)
            ->where('household_id', $householdId)
            ->countAllResults();
        
        if ($itemCount > 0) {
            return [
                'success' => false, 
                'message' => "Cannot delete place: {$itemCount} item(s) are assigned to it"
            ];
        }
        
        $this->where('id', $placeId)
            ->where('household_id', $householdId)
            ->delete();
        
        return ['success' => true, 'message' => 'Place deleted successfully'];
    }
}
