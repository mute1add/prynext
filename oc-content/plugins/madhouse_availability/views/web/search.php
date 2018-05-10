<fieldset class="first availability">

    <?php $detail = mdh_search_availability_start(); ?>
    <div class="control-group">
        <h3><?php _e("Availability", "madhouse_availability"); ?></h3>
        <div class="controls">
            <input class="mdh-availability" type="text" name="availabilityStart" value="<?php echo $detail ?>">
        </div>
    </div>
</fieldset>
<fieldset class="first availability">

    <?php if(mdh_availability_end_date_setting() >0) : ?>
        <?php $detail = mdh_search_availability_end(); ?>
        <div class="control-group">
            <?php if(mdh_availability_end_date_setting() != 1): ?>
                <h3><?php _e("During", "madhouse_availability"); ?></h3>
                <div class="controls">
                    <select name="availabilityEnd">
                        <option value=""><?php _e("Select a duration", "madhouse_availability"); ?></option>
                        <?php for ($i=1; $i <= 6; $i++): ?>
                            <option <?php echo ($detail == $i*30) ? 'selected="selected"': ""; ?> value="<?php echo $i*30; ?>"><?php echo $i ?>&nbsp;
                                <?php _e("Month", "madhouse_availability"); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</fieldset>

<script>
    $(".mdh-availability").datepicker({
        showOn:'focus',
        dateFormat: "yy-mm-dd",
        nextText: "",
        prevText:""
    });
</script>