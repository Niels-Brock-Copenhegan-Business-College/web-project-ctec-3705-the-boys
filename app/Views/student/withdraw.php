<?php
$success = $success ?? false;
$interest = $interest ?? null;
$token = $token ?? '';
$pageTitle = 'Withdraw Interest';
include __DIR__ . '/../layout/header.php';
?>
<section class="py-5">
  <div class="container text-center" style="max-width:480px">
    <?php if ($success): ?>
      <div class="alert alert-success" role="alert">
        ✅ Your interest registration has been successfully withdrawn.
      </div>

    <?php elseif (!empty($interest)): ?>
      <div class="alert alert-warning text-start" role="alert">
        <strong>Confirm withdrawal</strong><br>
        You are about to withdraw your interest in
        <strong><?= htmlspecialchars((string) ($interest['programme_title'] ?? 'this programme'), ENT_QUOTES) ?></strong>.
        This action will permanently remove your registration record.
      </div>

      <form method="POST" action="<?= base_url('/interest/withdraw/' . rawurlencode((string) $token)) ?>" class="d-grid gap-3">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-danger">Withdraw Interest</button>
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">Cancel</a>
      </form>

    <?php else: ?>
      <div class="alert alert-warning" role="alert">
        ⚠️ This withdrawal link is invalid or has already been used.
      </div>
      <a href="<?= base_url('/') ?>" class="btn btn-primary">Back to Programmes</a>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
