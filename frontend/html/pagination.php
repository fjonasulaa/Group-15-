<?php
function renderPagination($totalWines, $perPage, $params) {
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $totalPages = ceil($totalWines / $perPage);
    
    if ($totalPages <= 1) return;
    ?>
    <div style="text-align:center; padding: 20px 40px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&<?= http_build_query($params) ?>"
               style="padding: 8px 14px; margin: 3px; border: 1px solid #7b1e3a; border-radius: 6px;
                      text-decoration: none; color: <?= $i === $page ? '#fff' : '#7b1e3a' ?>;
                      background: <?= $i === $page ? '#7b1e3a' : 'white' ?>;">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php
}
?>