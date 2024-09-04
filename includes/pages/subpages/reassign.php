<?php
$history_id = trim( $_POST["oasiswf"] ); // phpcs:ignore
// sanitize data
$history_id = intval( sanitize_text_field( $history_id ) );

$task_user = ( isset( $_POST["task_user"] ) && $_POST["task_user"] ) ? trim( $_POST["task_user"] ) : get_current_user_id(); // phpcs:ignore
// sanitize data
$task_user = intval( sanitize_text_field( $task_user ) );

$ow_process_flow    = new OW_Process_Flow();
$ow_history_service = new OW_History_Service();
$workflow_service   = new OW_Workflow_Service();

$history_details     = $ow_history_service->get_action_history_by_id( $history_id );
$users               = array();
$user_info           = $ow_process_flow->get_users_in_step( $history_details->step_id );
$users               = $user_info["users"];
$assign_actors_label = OW_Utility::instance()->get_custom_workflow_terminology( 'assignActorsText' );
?>
<div id="reassgn-setting" class="info-setting">
    <div class="dialog-title"><strong><?php echo esc_html__( "Reassign", "oasisworkflow" ); ?></strong></div>
    <br class="clear">
    <div id="ow-reassign-messages" class="owf-hidden"></div>

    <div id="multi-actors-div" class="select-info" style="height:120px;">
        <label><?php echo esc_html( $assign_actors_label ) . " :"; ?></label>
        <div class="select-actors-div">
            <div class="select-actors-list">
                <label><?php echo esc_html__( "Available", "oasisworkflow" ); ?></label>
                <span class="assign-loading-span" style="float:right;margin-top:-18px;">&nbsp;</span>
                <br class="clear">
				<?php
				// Check if users are available for reassigning
				$user_count = count( (array) $users ) - 1;
				if ( 0 === $user_count ) {
					$alert_user = esc_html__( "No users found to reassign", "oasisworkflow" );
					// phpcs:ignore
					echo <<<SHOW_ALERT
               <script>
                  jQuery(document).ready(function(){
                     alert('$alert_user');
                     return false;
                  });
               </script>
SHOW_ALERT;
				}
				?>
                <p>
                    <select id="actors-list-select" name="actors-list-select" size=10 multiple="multiple">
						<?php
						// for executing performance lets check the above condition
						if ( 0 < $user_count && $users ) {
							foreach ( $users as $user ) {
								$lblNm = OW_Utility::instance()->get_user_name( $user->ID );
								if ( $task_user != $user->ID ) {
									echo "<option value={$user->ID}>$lblNm</option>"; // phpcs:ignore
								}
							}
						}
						?>
                    </select>
                </p>
            </div>
            <div class="select-actors-div-point">
                <a href="#" id="assignee-set-point"><img
                            src="<?php echo esc_url( OASISWF_URL ) . "img/role-set.png"; ?>"
                            style="border:0px;"/></a><br><br>
                <a href="#" id="assignee-unset-point"><img
                            src="<?php echo esc_url( OASISWF_URL ) . "img/role-unset.png"; ?>" style="border:0px;"/></a>
            </div>
            <div class="select-actors-list">
                <label><?php echo esc_html__( "Assigned", "oasisworkflow" ); ?></label><br class="clear">
                <p>
                    <select id="actors-set-select" name="actors-set-select" size=10 multiple="multiple"></select>
                </p>
            </div>
        </div>
        <br class="clear">
    </div>


    <div class="owf-text-info left full-width">
        <div class="left">
            <label><?php echo esc_html__( 'Comments:', 'oasisworkflow' ); ?></label>
        </div>
        <div class="left">
            <textarea id="reassignComments" class="workflow-comments"></textarea>
        </div>
    </div>
    <br class="clear">
    <p class="reassign-set">
        <input type="button" id="reassignSave" class="button-primary"
               value="<?php echo esc_attr__( "Save", "oasisworkflow" ); ?>"/>
        <span>&nbsp;</span>
        <a href="#" id="reassignCancel" style="color:blue;"><?php echo esc_html__( "Cancel", "oasisworkflow" ); ?></a>
    </p>
    <input type="hidden" id="action_history_id" name="action_history_id"
           value=<?php echo esc_attr( htmlspecialchars( $history_id, ENT_QUOTES, 'UTF-8' ) ); ?>/>
    <input type="hidden" id="task_user_inbox" name="task_user_inbox"
           value=<?php echo esc_attr( htmlspecialchars( $task_user, ENT_QUOTES, 'UTF-8' ) ); ?>/>
    <input type="hidden" name="owf_reassign_ajax_nonce" id="owf_reassign_ajax_nonce"
           value="<?php echo esc_attr( wp_create_nonce( 'owf_reassign_ajax_nonce' ) ); ?>"/>
    <br class="clear">
</div>