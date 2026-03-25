<?php
// redirect to home page when visiting the root index
header('Location: inicial.php');
exit;

// the old content is no longer needed, but could be kept for reference
/*
include __DIR__.'/include/head.php';

// simple navigation
echo '<nav style="text-align:center;margin:1rem 0;">';
echo '<a href="auth.php" style="text-decoration:none;color:#3498db;font-weight:bold;">Login / Cadastro</a>';
echo '</nav>';

include __DIR__.'/condicional.php';
?>

<?php
include __DIR__.'/include/footer.php';
*/

