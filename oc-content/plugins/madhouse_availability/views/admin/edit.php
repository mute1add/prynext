<h2><?php _e("Availability", mdh_current_plugin_name()); ?></h2>
<div class="box availability">

    <?php
        if( Session::newInstance()->_getForm('pp_d_start') !== '' ) {
            $detail = Session::newInstance()->_getForm('pp_d_start');
        } else {
            $detail = mdh_availability_start();
        }
    ?>
    <div class="form-row">
        <label class="form-label"><?php _e("Start", mdh_current_plugin_name()); ?></label>
        <div class="form-controls">
            <input class="mdh-availability" type="text" name="availabilityStart" value="<?php echo $detail; ?>">
        </div>
    </div>
    <?php if(mdh_availability_end_date_setting() >0) : ?>
        <?php
            if( Session::newInstance()->_getForm('pp_d_end') !== '' ) {
                $detail = Session::newInstance()->_getForm('pp_d_end');
            } else {
                if (mdh_availability_end_date_setting() == 1) {
                    $detail = mdh_availability_end();
                } else {
                    $detail = mdh_availability_duration();
                }
            }
        ?>

        <div class="form-row">
            <?php if(mdh_availability_end_date_setting() == 1): ?>
                <label class="form-label"><?php _e("End", mdh_current_plugin_name()); ?></label>
                <div class="form-controls">
                    <input class="mdh-availability" type="text" name="availabilityEnd" value="<?php echo $detail; ?>">
                </div>
                <?php else: ?>
                <label class="form-label"><?php _e("During", mdh_current_plugin_name()); ?></label>
                <div class="form-controls">
                    <select name="availabilityEnd">
                        <option value=""><?php _e("Select a duration", mdh_current_plugin_name()); ?></option>
                        <?php for ($i=1; $i <= 6; $i++): ?>
                            <option <?php echo ($detail == $i*30) ? 'selected="selected"': ""; ?> value="<?php echo $i*30; ?>"><?php echo $i ?>&nbsp;
                                <?php _e("Month", mdh_current_plugin_name()); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    $(".mdh-availability").datepicker({
        showOn:'focus',
        dateFormat: "yy-mm-dd",
        nextText: "",
        prevText:"",
        minDate: -90
    });
</script>