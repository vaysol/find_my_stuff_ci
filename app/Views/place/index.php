<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-geo-alt"></i> Places</h2>
        <a href="<?= base_url('/places/add') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Place
        </a>
    </div>
    
    <?php if (!empty($places)): ?>
    <div class="row">
        <?php foreach ($places as $place): ?>
        <div class="col-12 col-sm-6 col-lg-4 mb-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-geo-alt-fill text-primary"></i> 
                        <?= esc($place['name']) ?>
                    </h5>
                    
                    <?php if ($place['room']): ?>
                    <p class="text-muted mb-2">
                        <i class="bi bi-door-closed"></i> <?= esc($place['room']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if ($place['notes']): ?>
                    <p class="card-text small text-muted"><?= esc($place['notes']) ?></p>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <span class="badge bg-secondary">
                            <i class="bi bi-box"></i> <?= $place['item_count'] ?> item<?= $place['item_count'] != 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="<?= base_url("/places/edit/{$place['id']}") ?>" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form method="post" action="<?= base_url("/places/delete/{$place['id']}") ?>" 
                              class="flex-fill" onsubmit="return confirm('Delete this place?');">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> No Places Yet</h5>
        <p class="mb-0">Add places where you store your items to get started!</p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
