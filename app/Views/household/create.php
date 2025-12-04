<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="mb-4"><i class="bi bi-house-add"></i> Create New Household</h3>
                    
                    <form method="post" action="<?= base_url('/household/create') ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Household Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g., Beach House, Office" required autofocus>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Household
                            </button>
                            <a href="<?= base_url('/') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
