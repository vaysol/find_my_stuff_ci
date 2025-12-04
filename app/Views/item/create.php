<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-plus-circle"></i> Add New Item</h3>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="<?= base_url('/items/add') ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Item Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name') ?>" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3"><?= old('description') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" 
                                   value="<?= old('category') ?>" placeholder="e.g., Electronics, Clothing, Tools">
                        </div>
                        
                        <div class="mb-3">
                            <label for="assigned_place_id" class="form-label">Assigned Place</label>
                            <select class="form-select" id="assigned_place_id" name="assigned_place_id">
                                <option value="">Not assigned</option>
                                <?php foreach ($places as $place): ?>
                                <option value="<?= $place['id'] ?>" <?= old('assigned_place_id') == $place['id'] ? 'selected' : '' ?>>
                                    <?= esc($place['name']) ?><?= $place['room'] ? ' - ' . esc($place['room']) : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Where this item belongs</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_place_id" class="form-label">Last Seen Place</label>
                            <select class="form-select" id="last_place_id" name="last_place_id">
                                <option value="">Unknown</option>
                                <?php foreach ($places as $place): ?>
                                <option value="<?= $place['id'] ?>" <?= old('last_place_id') == $place['id'] ? 'selected' : '' ?>>
                                    <?= esc($place['name']) ?><?= $place['room'] ? ' - ' . esc($place['room']) : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Where you last saw this item</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Add Item
                            </button>
                            <a href="<?= base_url('/items') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
