<?php
    use app\components\TopMenu;
?>
<div>
    <?php $menu =  TopMenu::begin();?>
    <?=$menu->addMenu('menu1') ?>
    <?=$menu->addMenu('mene2') ?>
    <?php TopMenu::end();?>
</div>
