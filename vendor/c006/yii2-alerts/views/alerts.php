<?php
/** @var $id String */
/** @var $message String */
/** @var $alert_type String */
/** @var $close boolean */
/** @var $countdown int */
?>

<div id="<?= $id ?>" class="c006-alert <?= $alert_type ?>">
    <span class="message">
    <?= $message ?>
    </span>
    <?php if ($close) : ?>
        <span class="close">close</span>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('#<?= $id ?>').find('.close').click(function () {
                    jQuery('#<?= $id ?>').empty().remove();
                });
            });
        </script>
    <?php endif ?>
    <?php if ($countdown) : ?>
        <script type="text/javascript">
            jQuery(function () {
                setTimeout(function () {
                    jQuery('#<?= $id ?>')
                        .animate({
                            opacity: 0.0
                        }, 1000, function () {
                            jQuery('#<?= $id ?>').empty().remove();
                        });
                }, <?= $countdown * 1000 ?>);
            });
        </script>
    <?php endif ?>
</div>
