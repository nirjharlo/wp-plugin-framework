<p>
  <label for="metabox_field_name"><?php esc_attr_e( 'Custom Text', 'textdomain' ); ?></label>
  <br />
  <input class="widefat" type="text" name="metabox_field_name" id="metabox_field_name" value="<?php echo esc_attr( $this->e( $field_value ) ); ?>" />
</p>
