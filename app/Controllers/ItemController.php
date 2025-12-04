<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\PlaceModel;
use App\Models\HouseholdModel;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $placeModel;
    protected $householdModel;
    
    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->placeModel = new PlaceModel();
        $this->householdModel = new HouseholdModel();
        helper('household');
    }
    
    /**
     * List all items with search and filters
     */
    public function index()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $search = $this->request->getGet('search');
        $categoryFilter = $this->request->getGet('category');
        $placeFilter = $this->request->getGet('place');
        
        $items = $this->itemModel->getHouseholdItems(
            $householdId,
            $search,
            $categoryFilter,
            $placeFilter ? (int)$placeFilter : null
        );
        
        $places = $this->placeModel->getHouseholdPlaces($householdId);
        $stats = get_household_stats($householdId);
        $categories = $stats['categories'];
        $household = $this->householdModel->find($householdId);
        
        return view('item/index', [
            'items' => $items,
            'places' => $places,
            'categories' => $categories,
            'search' => $search,
            'category_filter' => $categoryFilter,
            'place_filter' => $placeFilter,
            'household' => $household
        ]);
    }
    
    /**
     * Show create item form
     */
    public function create()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $places = $this->placeModel->getHouseholdPlaces($householdId);
        $household = $this->householdModel->find($householdId);
        
        return view('item/create', [
            'places' => $places,
            'household' => $household
        ]);
    }
    
    /**
     * Store new item
     */
    public function store()
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $category = $this->request->getPost('category');
        $assignedPlaceId = $this->request->getPost('assigned_place_id');
        $lastPlaceId = $this->request->getPost('last_place_id');
        
        if (empty($name)) {
            session()->setFlashdata('error', 'Item name is required!');
            return redirect()->back()->withInput();
        }
        
        $this->itemModel->createItem(
            $householdId,
            $name,
            $description,
            $category,
            $assignedPlaceId,
            $lastPlaceId
        );
        
        session()->setFlashdata('success', "Item \"{$name}\" created successfully!");
        return redirect()->to('/items');
    }
    
    /**
     * Show edit item form
     */
    public function edit($itemId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $item = $this->itemModel->getItem($itemId, $householdId);
        
        if (!$item) {
            session()->setFlashdata('error', 'Item not found!');
            return redirect()->to('/items');
        }
        
        $places = $this->placeModel->getHouseholdPlaces($householdId);
        $household = $this->householdModel->find($householdId);
        
        return view('item/edit', [
            'item' => $item,
            'places' => $places,
            'household' => $household
        ]);
    }
    
    /**
     * Update item
     */
    public function update($itemId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $category = $this->request->getPost('category');
        $assignedPlaceId = $this->request->getPost('assigned_place_id');
        $lastPlaceId = $this->request->getPost('last_place_id');
        
        if (empty($name)) {
            session()->setFlashdata('error', 'Item name is required!');
            return redirect()->back()->withInput();
        }
        
        $this->itemModel->updateItem(
            $itemId,
            $householdId,
            $name,
            $description,
            $category,
            $assignedPlaceId,
            $lastPlaceId
        );
        
        session()->setFlashdata('success', "Item \"{$name}\" updated successfully!");
        return redirect()->to('/items');
    }
    
    /**
     * Delete item
     */
    public function delete($itemId)
    {
        $householdId = get_current_household();
        
        if (!$householdId) {
            session()->setFlashdata('error', 'No household selected!');
            return redirect()->to('/');
        }
        
        $item = $this->itemModel->getItem($itemId, $householdId);
        
        if ($item) {
            $this->itemModel->deleteItem($itemId, $householdId);
            session()->setFlashdata('success', "Item \"{$item['name']}\" deleted successfully!");
        } else {
            session()->setFlashdata('error', 'Item not found!');
        }
        
        return redirect()->to('/items');
    }
}
