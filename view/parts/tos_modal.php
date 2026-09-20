<?php
$tosMarkdown = file_get_contents(__DIR__ . '/../../ToS.md');
?>
<link rel="stylesheet" type="text/css" href="../css/tos_modal.css">

<dialog id="tosModal" class="tos-modal">
    <div class="tos-modal-head">
        <h2>利用規約</h2>
        <button type="button" class="tos-modal-close" aria-label="閉じる"><span>&times;</span></button>
    </div>
    <div class="tos-modal-body">
        <?= renderTosHtml($tosMarkdown) ?>
    </div>
</dialog>

<script src="../js/tos_modal.js"></script>
