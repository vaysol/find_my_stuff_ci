<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-envelope-check display-1 text-success"></i>
                    <h2 class="mt-4 mb-3">You're Invited!</h2>
                    
                    <p class="lead">
                        <?= esc($invitation['inviter_name']) ?> has invited you to join
                    </p>
                    <h4 class="text-primary"><?= esc($invitation['household_name']) ?></h4>
                    
                    <div class="mt-4">
                        <form method="post" action="<?= base_url("/invite/accept/{$invitation['token']}") ?>">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Accept Invitation
                            </button>
                        </form>
                    </div>
                    
                    <p class="text-muted mt-3 mb-0">
                        <small>Invited to: <?= esc($invitation['email']) ?></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
