<?php
/*
* get all ninja forms
*
*/
$forms = Ninja_Forms()->form()->get_forms();
foreach ( $forms as $form ) {
	$form_id 	= $form->get_id();
	$form_title 	= $form->get_setting( 'title' );
        
    //do your stuff..
}
/*
* get all ninja form submission based on form_id
*
* field_key field found under ninja form field settings > Administration > field key
*/
$submissions = Ninja_Forms()->form( $form_id )->get_subs();
//print_r($submissions);
if ( is_array( $submissions ) && count( $submissions ) > 0 ) {
    foreach($submissions as $submission) {
        $subid = $submission->get_id();
        $field_values = $submission->get_field_values();
        
        //submission date & seq no
        $subdate = get_the_time('m/d/Y', $subid);
        $sub_seq_num = get_post_meta( $subid, '_seq_num', true );
        
        //fetch data based on field_key 
        //$var = $field_values['field_key'];
        $test_name = $field_values['test_name'];
    }
}
/*
* get all ninja form submission between date range based on form_id
*
*/
add_shortcode('my_ninja_date_records',function(){
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'nf_sub',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_form_id',
                'value' => $form_id,
            ),
        ),
        'date_query' => array(
            'after' => $start_date,
            'before' => $end_date,
            'inclusive' => true,
        ),
        'fields' => 'ids',  // Just return IDs as get_sub() will get more info.
    );
    $submissions = array();
    $submissions = get_posts( $args );

    return $submissions;
});
/*
* get ninja form fieldID & fieldkey based on form ID
*
*/    
add_shortcode('my_ninja_fields',function(){
    global $wpdb;
    
    $blog_prefix = $wpdb->get_blog_prefix();
    if( (!empty($blog_prefix)) && (!empty($form_id)) ){
        $result = $wpdb->get_results ("SELECT * FROM  ".$blog_prefix."nf3_fields WHERE parent_id = $form_id");
    }
    return $result;
});
/*
* on ninja form submit get response
*
*/
add_action( 'wp_footer', function () { ?>
<script type="text/javascript">	
	jQuery( document ).ready( function() {
        jQuery( document ).on( "nfFormSubmitResponse", function(event, response, id ) {
            console.log('form_settings '+response.response.data.settings);
            console.log('submission id: '+response.response.data.actions.save.sub_id);
            
            //do your stuff..
        });
    });
</script>    
});