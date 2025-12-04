<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="mb-4"><i class="bi bi-person-plus"></i> Invite Member</h3>
                    <p class="text-muted">Invite someone to join <?= esc($household['name']) ?></p>
                    
                    <form method="post" action="<?= base_url('/household/invite') ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="friend@example.com" required autofocus>
                            <div class="form-text">They will receive an invitation link</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Invitation
                            </button>
                            <a href="<?= base_url('/household/settings') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
