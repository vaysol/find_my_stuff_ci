<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $createdField = null;
    protected $updatedField = 'last_updated';
    protected $protectFields = true;
    protected $allowedFields = ['household_id', 'name', 'description', 'category', 'assigned_place_id', 'last_place_id', 'last_updated'];
    
    // Validation
    protected $validationRules = [
        'household_id' => 'required|integer',
        'name' => 'required|min_length[2]'
    ];
    
    /**
     * Get all items for a household with optional filters
     */
    public function getHouseholdItems($householdId, $search = null, $categoryFilter = null, $placeFilter = null)
    {
        $builder = $this->select('items.*, 
                p1.name as assigned_place_name, 
                p1.room as assigned_place_room,
                p2.name as last_place_name,
                p2.room as last_place_room')
            ->join('places p1', 'items.assigned_place_id = p1.id', 'left')
            ->join('places p2', 'items.last_place_id = p2.id', 'left')
            ->where('items.household_id', $householdId);
        
        // Search filter
        if ($search) {
            $builder->groupStart()
                ->like('items.name', $search)
                ->orLike('items.description', $search)
                ->orLike('items.category', $search)
                ->orLike('p1.name', $search)
                ->orLike('p2.name', $search)
                ->groupEnd();
        }
        
        // Category filter
        if ($categoryFilter) {
            $builder->where('items.category', $categoryFilter);
        }
        
        // Place filter
        if ($placeFilter) {
            $builder->groupStart()
                ->where('items.assigned_place_id', $placeFilter)
                ->orWhere('items.last_place_id', $placeFilter)
                ->groupEnd();
        }
        
        return $builder->orderBy('items.last_updated', 'DESC')->findAll();
    }
    
    /**
     * Get item by ID within household context
     */
    public function getItem($itemId, $householdId)
    {
        return $this->select('items.*, 
                p1.name as assigned_place_name,
                p2.name as last_place_name')
            ->join('places p1', 'items.assigned_place_id = p1.id', 'left')
            ->join('places p2', 'items.last_place_id = p2.id', 'left')
            ->where('items.id', $itemId)
            ->where('items.household_id', $householdId)
            ->first();
    }
    
    /**
     * Create a new item
     */
    public function createItem($householdId, $name, $description, $category, $assignedPlaceId, $lastPlaceId)
    {
        return $this->insert([
            'household_id' => $householdId,
            'name' => trim($name),
            'description' => trim($description),
            'category' => trim($category),
            'assigned_place_id' => $assignedPlaceId ?: null,
            'last_place_id' => $lastPlaceId ?: null,
            'last_updated' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Update an existing item
     */
    public function updateItem($itemId, $householdId, $name, $description, $category, $assignedPlaceId, $lastPlaceId)
    {
        return $this->where('id', $itemId)
            ->where('household_id', $householdId)
            ->set([
                'name' => trim($name),
                'description' => trim($description),
                'category' => trim($category),
                'assigned_place_id' => $assignedPlaceId ?: null,
                'last_place_id' => $lastPlaceId ?: null,
                'last_updated' => date('Y-m-d H:i:s')
            ])
            ->update();
    }
    
    /**
     * Delete an item
     */
    public function deleteItem($itemId, $householdId)
    {
        return $this->where('id', $itemId)
            ->where('household_id', $householdId)
            ->delete();
    }
    
    /**
     * Get all unique categories for a household
     */
    public function getCategories($householdId)
    {
        return $this->distinct()
            ->select('category')
            ->where('household_id', $householdId)
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->orderBy('category')
            ->findAll();
    }
}
