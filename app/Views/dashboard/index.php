<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <p class="text-muted">Household: <?= esc($household['name']) ?></p>
    
    <div class="row mt-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-box display-4 text-primary"></i>
                    <h3 class="mt-3"><?= $stats['total_items'] ?></h3>
                    <p class="text-muted mb-0">Total Items</p>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-geo-alt display-4 text-success"></i>
                    <h3 class="mt-3"><?= $stats['total_places'] ?></h3>
                    <p class="text-muted mb-0">Total Places</p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-plus-circle"></i> Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('/items/add') ?>" class="btn btn-primary">
                            <i class="bi bi-box"></i> Add New Item
                        </a>
                        <a href="<?= base_url('/places/add') ?>" class="btn btn-success">
                            <i class="bi bi-geo-alt"></i> Add New Place
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (!empty($stats['recent_items'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Assigned Place</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_items'] as $item): ?>
                                <tr>
                                    <td><strong><?= esc($item['name']) ?></strong></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td>
                                        <?php if ($item['assigned_place_name']): ?>
                                            <i class="bi bi-geo-alt"></i> <?= esc($item['assigned_place_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($item['last_updated'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info mt-4">
        <h5><i class="bi bi-info-circle"></i> Get Started!</h5>
        <p class="mb-0">You haven't added any items yet. Click "Add New Item" above to get started!</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
