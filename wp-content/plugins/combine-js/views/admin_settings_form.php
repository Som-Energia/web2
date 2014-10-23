            <div class="wrap">
                <div id="icon-options-general" class="icon32"><br/></div>
                <h2><?php _e( self::pname, self::nspace ); ?> Settings</h2>
<?php if ( isset( $_POST[self::nspace . '_update_settings'] ) ): ?>
                <div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Settings saved.</strong></p></div>
<?php endif; ?>
<?php if ( ! file_exists( $this->tmp_dir ) ): ?>
                <div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Temporary directory does not exist. You will need to manually create this directory by using these commands:</strong> <ul><li>mkdir <?php echo $this->tmp_dir; ?>;</li><li>chmod 777 <?php echo $this->tmp_dir; ?>;</li></ul></p></div>
<?php elseif ( ! file_exists( $this->js_settings_path ) ): ?>
                <div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Settings file does not exist. You will need to manually create this file by using these commands:</strong> <ul><li>mkdir <?php echo $this->js_settings_path; ?>;</li><li>chmod 666 <?php echo $this->js_settings_path; ?>;</li></ul></p></div>
<?php elseif( ! is_writable( $this->js_settings_path ) ): ?>
                <div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Settings file is not writable. You will need to manually fix permissions by using this command:</strong> <ul><li>chmod 666 <?php echo $this->js_settings_path; ?>;</li></ul></p></div>
<?php endif; ?>
                <form method="post">
                    <table class="form-table">
<?php foreach ( $this->settings_fields as $key => $val ): ?>
                        <tr valign="top">
<?php if ( $val['type'] == 'legend' ): ?>
                            <th colspan="2" class="legend" scope="row"><strong><?php echo __( $val['label'], self::nspace ); ?></strong></th>
<?php else: ?>
                            <th scope="row"><label for="<?php echo $key; ?>"><?php echo __( $val['label'], self::nspace ); ?></label><?php if ( $val['instruction'] ): ?><br><em><?php echo __( $val['instruction'], self::nspace ); ?></em><?php endif; ?></th>
                            <td>
<?php if( $val['type'] == 'money'): ?>
                                <span class="dollar-sign">$</span>
<?php endif; ?>
<?php if( $val['type'] == 'money' || $val['type'] == 'text' || $val['type'] == 'password' ): ?>
<?php
        if ( $val['type'] == 'money' ) $val['type'] = 'text';
        $value = $this->get_settings_value( $key );
        if ( ! @strlen( $value ) ) $value = $val['default'];
?>
                                <input name="<?php echo $key; ?>" type="<?php echo $val['type']; ?>" id="<?php echo $key; ?>" class="regular-text" value="<?php echo stripslashes( htmlspecialchars( $value ) ); ?>" />
<?php elseif ( $val['type'] == 'select' ): ?>
<?php
        $value = $this->get_settings_value( $key );
        if ( ! @strlen( $value ) ) $value = $val['default'];
?>
                                <?php echo $this->select_field( $key, $val['values'], $value ); ?>
<?php elseif( $val['type'] == 'textarea' ): ?>
                                <textarea class="regular-text" cols="60" rows="10" name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo stripslashes( htmlspecialchars( $this->get_settings_value( $key ) ) ); ?></textarea>
<?php endif; ?>
<?php if( isset( $val['description'] ) ): ?>
                                <span class="description"><?php echo $val['description']; ?></span>
<?php endif; ?>
                            </td>
<?php endif; ?>
                        </tr>
<?php endforeach; ?>
                    </table>
                    <input type="hidden" name="<?php echo self::nspace; ?>_update_settings" value="1" />
                    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
                </form>
            </div>
