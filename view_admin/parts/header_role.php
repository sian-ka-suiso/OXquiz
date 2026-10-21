<?php
//**************************************************
// ヘッダー用：ログイン中ユーザーの権限表示
// ログイン時にセッションへ保存した権限フラグから表示用のロールを作る
//**************************************************
    $header_roles = getRoleLabels([
        'is_admin'   => isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0,
        'is_teacher' => isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0,
    ]);
?>
                <div class="admin-header-role">
                    ログイン権限：
                    <?php foreach ($header_roles as $header_role): ?>
                    <span class="badge <?= V2H($header_role['class']) ?>"><?= V2H($header_role['label']) ?></span>
                    <?php endforeach; ?>
                </div>
