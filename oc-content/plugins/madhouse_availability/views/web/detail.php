<p><strong><?php _e("Availability:", "madhouse_availability");?></strong>&nbsp;
<?php echo osc_format_date(mdh_availability_start()); ?>&nbsp;
<?php if(mdh_availability_has_end()): ?>

    <?php _e("until", "madhouse_availability");?>&nbsp;
    <?php echo osc_format_date(mdh_availability_end()); ?>&nbsp;

    <?php $duration = mdh_availability_duration(); ?>
    <?php if($duration == 1):?>
        (<?php printf(__("%d day", "madhouse_availability"), mdh_availability_duration());?>)
    <?php else: ?>
        (<?php printf(__("%d days", "madhouse_availability"), mdh_availability_duration());?>)
    <?php endif; ?>
<?php endif; ?>
</p>