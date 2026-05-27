<?php
$entries = $entries ?? [];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Superadmin Logs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{padding:24px;font-family:Inter,system-ui,Roboto,sans-serif}</style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Superadmin logs</h3>
        <a href="<?= base_url('/superadmin') ?>" class="btn btn-outline-secondary">Back to dashboard</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($entries)): ?>
                <div class="alert alert-info mb-0">No log entries found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Level</th>
                                <th>User</th>
                                <th>Message</th>
                                <th>Details</th>
                                        <th></th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($entries as $entry):
                                    $id = $entry['id'] ?? null;
                                    $actor = $entry['actor'] ?? 'System';
                                    $details = $entry['details'] ?? ($entry['message'] ?? '');
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string) ($entry['time'] ?? ''), ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string) ($entry['level'] ?? ''), ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string) $actor, ENT_QUOTES) ?></td>
                                        <td><?= htmlspecialchars((string) ($entry['message'] ?? ''), ENT_QUOTES) ?></td>
                                        <td class="text-wrap" style="max-width: 520px;"><?= htmlspecialchars((string) $details, ENT_QUOTES) ?></td>
                                        <td class="text-end">
                                            <?php if ($id !== null): ?>
                                                <form method="post" action="<?= base_url('/superadmin/logs/delete') ?>" onsubmit="return confirm('Delete this log entry?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id, ENT_QUOTES) ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>