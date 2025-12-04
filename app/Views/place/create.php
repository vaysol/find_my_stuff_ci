<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-plus-circle"></i> Add New Place</h3>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="<?= base_url('/places/add') ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Place Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name') ?>" placeholder="e.g., Kitchen Cabinet" 
                                   required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="room" class="form-label">Room</label>
                            <input type="text" class="form-control" id="room" name="room" 
                                   value="<?= old('room') ?>" placeholder="e.g., Kitchen, Bedroom">
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" 
                                      rows="3" placeholder="Additional details about this place"><?= old('notes') ?></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Add Place
                            </button>
                            <a href="<?= base_url('/places') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
