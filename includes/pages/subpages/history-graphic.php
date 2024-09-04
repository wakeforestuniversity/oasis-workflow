<?php

global $wpdb, $chkResult;

$ow_workflow_service = new OW_Workflow_Service();

// phpcs:ignore
if ( is_admin() && preg_match_all( '/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
	wp_enqueue_script( 'owf-workflow-history',
		OASISWF_URL . 'js/pages/subpages/history-graphic.js',
		'',
		OASISWF_VERSION,
		true );
}

$workflow = null;
// phpcs:ignore
$post_id = intval( sanitize_text_field( $_GET['post'] ) );
if ( is_numeric( $chkResult ) ) {
	$sql = "SELECT C.ID, C.wf_info
   			FROM (
   				(SELECT * FROM " . OW_Utility::instance()->get_action_history_table_name() . " WHERE ID = $chkResult) AS A
   				LEFT JOIN " . OW_Utility::instance()->get_workflow_steps_table_name() . " AS B
   				ON A.step_id = B.ID
   				LEFT JOIN " . OW_Utility::instance()->get_workflows_table_name() . " AS C
   				ON B.workflow_id = C.ID
   			)";
	// phpcs:ignore
	$workflow = $wpdb->get_row( $sql );
}
if ( $workflow ) {

	$sql       = "SELECT * FROM " . OW_Utility::instance()->get_action_history_table_name() . " WHERE ID <= $chkResult AND (action_status = 'processed' OR action_status = 'assignment') AND post_id = %d ORDER BY ID";
	$processes = $wpdb->get_results( $wpdb->prepare( $sql, $post_id ) ); // phpcs:ignore

	if ( $processes ) {

		$startid = "";
		foreach ( $processes as $process ) {
			if ( $startid ) {
				$newconns[] = $ow_workflow_service->get_connection( $workflow, $startid, $process->step_id );
			}
			$startid = $process->step_id;
		}

		$current_step_id = $ow_workflow_service->get_gpid_dbid( $workflow->wf_info, $startid );

		$wf_info = $workflow->wf_info;
	}

	echo "<script type='text/javascript'>
			var wfPluginUrl  = '" . esc_url( OASISWF_URL ) . "' ;
			var stepinfo='" . esc_attr( $wf_info ) . "' ;
			var currentStepGpId='" . esc_attr( $current_step_id ) . "' ;
		</script>";
}
?>
<div id="workflow-area" style="position:relative;width:100%;"></div>
<br class="clear">