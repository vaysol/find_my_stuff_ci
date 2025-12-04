<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box"></i> Items</h2>
        <a href="<?= base_url('/items/add') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Item
        </a>
    </div>
    
    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="<?= base_url('/items') ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search items..." 
                           value="<?= esc($search ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat) ?>" <?= ($category_filter ?? '') == $cat ? 'selected' : '' ?>>
                            <?= esc($cat) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="place">
                        <option value="">All Places</option>
                        <?php foreach ($places as $place): ?>
                        <option value="<?= $place['id'] ?>" <?= ($place_filter ?? '') == $place['id'] ? 'selected' : '' ?>>
                            <?= esc($place['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (!empty($items)): ?>
    <!-- Items Table (Desktop) -->
    <div class="card">
        <div class="card-body">
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Assigned Place</th>
                            <th>Last Seen</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= esc($item['name']) ?></strong>
                                <?php if ($item['description']): ?>
                                <br><small class="text-muted"><?= esc($item['description']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item['category']) ?></td>
                            <td>
                                <?php if ($item['assigned_place_name']): ?>
                                    <i class="bi bi-geo-alt text-success"></i> 
                                    <?= esc($item['assigned_place_name']) ?>
                                    <?php if ($item['assigned_place_room']): ?>
                                        <br><small class="text-muted"><?= esc($item['assigned_place_room']) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Not assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['last_place_name']): ?>
                                    <i class="bi bi-geo-alt-fill text-info"></i> 
                                    <?= esc($item['last_place_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($item['last_updated'])) ?></td>
                            <td>
                                <a href="<?= base_url("/items/edit/{$item['id']}") ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= base_url("/items/delete/{$item['id']}") ?>" 
                                      class="d-inline" onsubmit="return confirm('Delete this item?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="d-md-none">
                <?php foreach ($items as $item): ?>
                <div class="card mb-3 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1"><?= esc($item['name']) ?></h5>
                                <?php if ($item['description']): ?>
                                <p class="card-text text-muted small mb-2"><?= esc($item['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <?php if ($item['category']): ?>
                            <span class="badge bg-secondary me-2">
                                <i class="bi bi-tag"></i> <?= esc($item['category']) ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($item['assigned_place_name']): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-geo-alt"></i> <?= esc($item['assigned_place_name']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="text-muted small mb-3">
                            <i class="bi bi-clock"></i> Updated <?= date('M j, Y', strtotime($item['last_updated'])) ?>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="<?= base_url("/items/edit/{$item['id']}") ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="post" action="<?= base_url("/items/delete/{$item['id']}") ?>" 
                                  class="flex-fill" onsubmit="return confirm('Delete this item?');">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> No Items Found</h5>
        <p class="mb-0">
            <?= ($search || $category_filter || $place_filter) ? 'Try adjusting your search filters.' : 'Start by adding your first item!' ?>
        </p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
