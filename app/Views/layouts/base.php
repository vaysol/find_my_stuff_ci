<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Item Manager' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom Mobile Responsive CSS -->
    <link rel="stylesheet" href="<?= base_url('css/mobile-responsive.css') ?>">
</head>

<body>
    <?php if (session()->get('user_id')): ?>
    <!-- Navigation for Authenticated Users -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-box-seam"></i> Item Manager
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() == '' || uri_string() == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'items') !== false ? 'active' : '' ?>" href="<?= base_url('/items') ?>">
                            <i class="bi bi-box"></i> Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(uri_string(), 'places') !== false ? 'active' : '' ?>" href="<?= base_url('/places') ?>">
                            <i class="bi bi-geo-alt"></i> Places
                        </a>
                    </li>
                </ul>

                <!-- Household Switcher & User Menu -->
                <ul class="navbar-nav">
                    <?php 
                    helper('household');
                    $user_households = get_user_households();
                    $current_household_id = get_current_household();
                    $household = isset($household) ? $household : null;
                    ?>
                    <!-- Household Switcher -->
                    <?php if (!empty($user_households)): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-house"></i>
                            <?= $household ? esc($household['name']) : 'Select Household' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php foreach ($user_households as $h): ?>
                            <li>
                                <a class="dropdown-item <?= $current_household_id == $h['id'] ? 'active' : '' ?>" 
                                   href="<?= base_url("/household/switch/{$h['id']}") ?>">
                                    <i class="bi bi-house-door-<?= $current_household_id == $h['id'] ? 'fill' : 'check' ?>"></i>
                                    <?= esc($h['name']) ?>
                                    <span class="badge bg-<?= $h['role'] == 'owner' ? 'danger' : ($h['role'] == 'admin' ? 'warning' : 'secondary') ?> ms-2">
                                        <?= esc($h['role']) ?>
                                    </span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/household/create') ?>">
                                    <i class="bi bi-plus-circle"></i> Create New Household
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('user_name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?= esc(session()->get('user_email')) ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($household): ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/household/settings') ?>">
                                    <i class="bi bi-gear"></i> Household Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('/logout') ?>">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="<?= session()->get('user_id') ? 'container' : '' ?> my-4">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="text-center text-muted py-3 mt-5">
        <div class="container">
            <p class="mb-0">Item Manager © <?= date('Y') ?> | Built with CodeIgniter 4 & Bootstrap</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
