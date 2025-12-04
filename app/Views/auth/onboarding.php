<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4"><i class="bi bi-house-add"></i> Welcome!</h2>
                    <p class="text-center text-muted">Let's create your first household</p>
                    
                    <form method="post" action="<?= base_url('/onboarding') ?>">
                        <div class="mb-3">
                            <label for="household_name" class="form-label">Household Name</label>
                            <input type="text" class="form-control" id="household_name" 
                                   name="household_name" placeholder="e.g., My Home, Smith Family" 
                                   required autofocus>
                            <div class="form-text">You can create more households later</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Create Household
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
