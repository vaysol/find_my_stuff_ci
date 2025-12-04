<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 text-center">
            <h1 class="display-4"><i class="bi bi-box-seam"></i> Item Manager</h1>
            <p class="lead mt-3">Keep track of your items and never lose anything again!</p>
            
            <div class="row mt-5">
                <div class="col-md-4">
                    <i class="bi bi-search display-1 text-primary"></i>
                    <h4 class="mt-3">Find Items Quickly</h4>
                    <p>Search and filter your items by category, place, or name</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-people display-1 text-success"></i>
                    <h4 class="mt-3">Multi-User Households</h4>
                    <p>Create households and collaborate with family members</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-geo-alt display-1 text-warning"></i>
                    <h4 class="mt-3">Track Locations</h4>
                    <p>Assign items to places and always know where they are</p>
                </div>
            </div>
            
            <div class="mt-5">
                <a href="<?= base_url('/register') ?>" class="btn btn-primary btn-lg me-2">
                    <i class="bi bi-person-plus"></i> Get Started
                </a>
                <a href="<?= base_url('/login') ?>" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
