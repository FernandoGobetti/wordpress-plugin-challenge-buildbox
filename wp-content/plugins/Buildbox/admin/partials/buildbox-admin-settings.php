<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Buildbox
 * @subpackage Buildbox/admin/partials
 */

$bb_settings = get_option('bb_settings');

$bb_settings = $bb_settings['buildbox_settings'];

if (empty($bb_settings)) {
    $bb_settings = [
        'basic_settings' => [
            "allowed" => '1',
            "post_type" => [

            ]
        ]];
}
?>
<div class="wrap bb-wrap">
    <div class="bb-header">
        <h3>ShortCode para utilizar - [buildbox-top-liked]</h3>
    </div>
    <div class="bb-clear"></div>
    <h2 class="nav-tab-wrapper wp-clearfix">
        <?php
        $bb_tabs = array(
            'basic' => array('label' => 'Basic Settings')
        );
        $bb_tab_counter = 0;
        foreach ($bb_tabs as $bb_tab => $bb_tab_detail) {
            $bb_tab_counter++;
            ?>
            <a href="javascript:void(0);"
               class="nav-tab <?php echo ($bb_tab_counter == 1) ? 'nav-tab-active' : ''; ?> bb-tab-trigger"
               data-settings-ref="<?php echo $bb_tab; ?>"><?php echo $bb_tab_detail['label']; ?></a>
            <?php
        }
        ?>
    </h2>

    <div class="bb-settings-section-wrap">
        <form class="bb-settings-form">
            <div class="bb-settings-section" data-settings-ref="basic">
                <div class="bb-field-wrap">
                    <label>Allowed</label>
                    <div class="bb-field">
                        <input type="checkbox" name="buildbox_settings[basic_settings][allowed]"
                               value="1" <?php echo (!empty($bb_settings['basic_settings']['allowed'])) ? 'checked="checked"' : ''; ?>/>
                    </div>
                </div>
                <div class="bb-field-wrap">
                    <label>Post Types</label>
                    <div class="bb-field">
                        <?php
                        $post_types = get_post_types(array('public' => true), 'object');
                        $checked_post_types = (!empty($bb_settings['basic_settings']['post_types'])) ? $bb_settings['basic_settings']['post_types'] : array();
                        foreach ($post_types as $post_type_name => $post_type_object) {
                            ?>
                            <label class="bb-checkbox-label">
                                <input type="checkbox" name="buildbox_settings[basic_settings][post_types][]"
                                       value="<?php echo esc_attr($post_type_name); ?>" <?php echo (in_array($post_type_name, $checked_post_types)) ? 'checked="checked"' : ''; ?>
                                       class="bb-form-field"/><?php echo esc_attr($post_type_object->label); ?>
                            </label>
                            <?php
                        }
                        ?>
                        <p class="description">
                            Please uncheck all of these if you are wiling to generate the like dislike icon through
                            custom function.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bb-field-wrap bb-settings-action">
                <label></label>
                <div class="bb-field">
                    <input type="submit" class="bb-settings-save-trigger button-primary"
                           value="Save settings"/>
                </div>
            </div>
        </form>

    </div>
    <div class="bb-info-wrap" style="display:none;">
        <span class="bb-info">'Please wait</span>
        <span class="dashicons dashicons-dismiss bb-close-info"></span>
    </div>
</div>