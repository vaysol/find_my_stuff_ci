<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2><i class="bi bi-gear"></i> Household Settings</h2>
    <p class="text-muted">Manage <?= esc($household['name']) ?></p>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Household Details</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('/household/settings') ?>">
                        <input type="hidden" name="action" value="update_name">
                        <div class="mb-3">
                            <label for="name" class="form-label">Household Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= esc($household['name']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Name
                        </button>
                    </form>
                    
                    <?php if ($user_role === 'owner'): ?>
                    <hr>
                    <form method="post" action="<?= base_url('/household/settings') ?>" 
                          onsubmit="return confirm('Are you sure you want to delete this household? This action cannot be undone.');">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete Household
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Members</h5>
                    <?php if (in_array($user_role, ['owner', 'admin'])): ?>
                    <a href="<?= base_url('/household/invite') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-person-plus"></i> Invite
                    </a>
                    <?php endif; ?>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($members as $member): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= esc($member['name']) ?></strong>
                            <br>
                            <small class="text-muted"><?= esc($member['email']) ?></small>
                        </div>
                        <div>
                            <span class="badge bg-<?= $member['role'] == 'owner' ? 'danger' : ($member['role'] == 'admin' ? 'warning' : 'secondary') ?>">
                                <?= esc($member['role']) ?>
                            </span>
                            <?php if (in_array($user_role, ['owner', 'admin']) && $member['role'] !== 'owner'): ?>
                            <form method="post" action="<?= base_url("/household/member/remove/{$member['user_id']}") ?>" 
                                  class="d-inline" onsubmit="return confirm('Remove this member?');">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if (!empty($pending_invitations) && in_array($user_role, ['owner', 'admin'])): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Invitations</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($pending_invitations as $inv): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= esc($inv['email']) ?></strong>
                            <br>
                            <small class="text-muted">Invited by <?= esc($inv['inviter_name']) ?></small>
                        </div>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
