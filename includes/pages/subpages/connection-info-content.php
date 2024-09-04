<div id="connection-setting">
    <div class="dialog-title"><strong><?php esc_html_e( 'Connection Settings', 'oasisworkflow' ); ?></strong></div>
    <div class="connection-status">
        <table class="relation">
            <tr>
                <th><?php esc_html_e( 'Current Connection', 'oasisworkflow' ); ?> :</th>
                <td><?php esc_html_e( 'Source', 'oasisworkflow' ); ?></td>
                <td><?php echo esc_html( wptexturize( '---' ) ); ?></td>
                <td><label id="source_name_lbl"></label></td>
            </tr>
            <tr>
                <td></td>
                <td><?php esc_html_e( 'Target', 'oasisworkflow' ); ?></td>
                <td><?php echo esc_html( wptexturize( '---' ) ); ?></td>
                <td><label id="target_name_lbl"></label></td>
            </tr>
        </table>
    </div>
    <br class="clear">
    <div class="step-status">
        <table>
            <tr>
                <th><?php esc_html_e( 'Post Status :', 'oasisworkflow' ); ?></th>
                <td>
                    <select id="step-status-select" name="step-status-select">
                        <option value=""></option>
						<?php
						$status_array = get_post_stati( array( 'show_in_admin_status_list' => true ), 'objects' );
						/**
						 * Do not show post statuses like trash, publish, schedule
						 *
						 * @since 2.1
						 */
						$block_post_statuses = array(
							'trash',
							'publish',
							'future'
						);

						$block_post_statuses = apply_filters( 'owf_block_post_statuses_on_conn_settings', $block_post_statuses );

						foreach ( $status_array as $status_slug => $status_object ) {
							if ( in_array( $status_slug, $block_post_statuses ) ) {
								continue;
							}
							echo "<option value='" . esc_attr( $status_slug ) . "'>" . esc_html( $status_object->label ) . "</option>";
						}
						?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <br class="clear">
    <div class="connection-path">
        <table>
            <tr>
                <th><?php esc_html_e( 'Path :', 'oasisworkflow' ); ?></th>
				<?php
				$oasiswf_path = get_site_option( 'oasiswf_path' );
				if ( $oasiswf_path ) {
					foreach ( $oasiswf_path as $k => $v ) {
						$str = '<td>
                         <label for="path-opt-' . esc_attr( $v[1] ) . '">
									<input type="radio" id="path-opt-' . esc_attr( $v[1] ) . '" name="path-opt" value="' . esc_attr( $v[1] ) . '" ' . '/> ' . $v[0] . '
                              </label>
								</td>';
						echo $str; // phpcs:ignore
					}
				}
				?>
            </tr>
        </table>
    </div>
    <div class="changed-data-set">
        <div class="right button-spacing">
            <input type="button" id="connection-setting-save" class="button-primary"
                   value="<?php esc_attr_e( 'Save', 'oasisworkflow' ); ?>"/>
        </div>
        <div class="right button-link-spacing">
            <a href="#" id="connection-setting-cancel"><?php esc_html_e( 'Cancel', 'oasisworkflow' ); ?></a>
        </div>
    </div>
    <br class="clear"/>
</div>