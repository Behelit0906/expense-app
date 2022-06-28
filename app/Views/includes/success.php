<?php if(isset($_SESSION['successMessage']) && !empty($_SESSION['successMessage'])): ?>
    <div>
        <?= $_SESSION['successMessage']; ?>
    </div>
<?php endif; ?>