
<h1 class="title"><?= (true) ? get_field('text-for-title',4) : get_the_title() ?></h1>

<?php if (get_field('custom-subtitle',4)) { ?>
    <h2><?= get_field('text-for-subtitle,4') ?></h2>
<?php }


