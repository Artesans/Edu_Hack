<?php
/**
Plugin Name: Eduhack Projects Widget
Plugin URI:  www.artesans.eu
Description: Basic WordPress Plugin Header Comment
Version:     1.0
Author:      Artesans
Author URI:  www.artesans.eu
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /
*/

function eduhack_plugin_scripts() {
    wp_enqueue_script('eduhack-utils', plugins_url('/js/utils.js', __FILE__), array( 'eduhack-color' ), time(), false  );
    wp_enqueue_script('eduhack-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), time(), false  );
    wp_enqueue_script('eduhack-color', plugins_url('/js/jscolor.min.js', __FILE__), array( 'jquery' ), time(), false  );
    wp_enqueue_style('eduhack-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array( ), time(), false  );
    wp_enqueue_style('eduhack-font-a-css', plugins_url('/css/font-awesome.min.css', __FILE__), array( ), time(), false  );
    wp_enqueue_style('eduhack-admin-css', plugins_url('/css/admin-styles.css', __FILE__), array( ), time(), false  );

    wp_enqueue_script( 'wp-media-uploader', plugins_url('/js/wp_media_uploader.js', __FILE__) , array(), time(), false );
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'eduhack_plugin_scripts' );


function custom_widget_scripts(){
    wp_enqueue_style('eduhack-styles', plugins_url('/css/styles.css', __FILE__), array( ), time(), false  );
    wp_enqueue_script('eduhack-front-utils', plugins_url('/js/front-utils.js', __FILE__), array( 'jquery' ), time(), false  );
}
add_action( 'wp_enqueue_scripts', 'custom_widget_scripts' );


function create_project_posttype() {

    register_post_type( 'cpt_project',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Projects' ),
                'singular_name' => __( 'Project' )
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon'   => 'dashicons-clipboard',
        )
    );

}
// Hooking up our function to theme setup
add_action( 'init', 'create_project_posttype' );


/**
 * Adds a meta box to the post editing screen
 */
function projects_custom_meta() {
    add_meta_box( 'projects_meta', __( 'Configuració', 'projects-textdomain' ), 'projects_meta_callback', 'cpt_project' );
}
add_action( 'add_meta_boxes', 'projects_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function projects_meta_callback( $post ) {
    $stored_meta = get_post_meta( $post->ID );
    //print_r($stored_meta);

    $status = unserialize($stored_meta['widget-status'][0]);
    $status = unserialize($status);

    ?>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-team">TEAM</a></li>
            <li><a href="#tabs-description">DESCRIPTION</a></li>
            <li><a href="#tabs-status">STATUS</a></li>
            <?php if ( current_user_can( 'manage_options' ) ) {?>
                <li><a href="#tabs-configuration">CONFIGURATION</a></li>
            <?php }?>
        </ul>
        <div id="tabs-team">
            <section class="team">
                <h2 class="projects-config"><?php _e("Team", 'eduhack-projects-widget');?></h2>
                <div class="team-container">
                    <?php if($stored_meta['widget-team_name'][0]!=''){
                        $i = 0;
                        $team_name = unserialize($stored_meta['widget-team_name'][0]);
                        $team_name = unserialize($team_name);
                        $team_img = unserialize($stored_meta['widget-team_img'][0]);
                        $team_img = unserialize($team_img);
                        $team_school = unserialize($stored_meta['widget-team_school'][0]);
                        $team_school = unserialize($team_school);

                        for ($i=0; $i<count($team_name); $i++){
                            $this_image = wp_get_attachment_image_src( $team_img[$i], 'thumbnail' );

                            $button_text = (empty($this_image)) ? __("Upload image", 'eduhack-projects-widget') : __("Change image", 'eduhack-projects-widget');
                            ?>
                            <div>
                                <label for="meta-text" class="team-label"><?php _e( 'User Name', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="team[name][]" value="<?php echo $team_name[$i];?>">
                                <label for="meta-text" class="team-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                                <input class="image-url-<?php echo $i;?>" type="hidden" name="team[image][]" value="<?php echo $team_img[$i];?>" />
                                <input type="button" class="button upload-button button-<?php echo $i;?>" value="<?php echo $button_text;?>" data-buttonid="<?php echo $i;?>" data-att-image="image-url-" data-img-src="image-src-"/>
                                <img src="<?php echo $this_image[0]; ?>" class="team-img image-src-<?php echo $i;?>"/>
                                <label for="meta-text" class="team-label"><?php _e( 'School', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="team[school][]" value="<?php echo $team_school[$i];?>"><a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        <?php }?>

                    <?php }else{?>
                            <div>
                                <label for="meta-text" class="team-label"><?php _e( 'User Name', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="team[name][]">
                                <label for="meta-text" class="team-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                                <input class="image-url-0" type="text" name="team[image][]" />
                                <input type="button" class="button upload-button" value="Upload Image" data-buttonid="0" data-att-image="image-url-" data-img-src="image-src-"/>
                                <label for="meta-text" class="team-label"><?php _e( 'School', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="team[school][]">

                            </div>
                    <?php }?>
                </div>
                <div class="add_form_field" data-count="<?php echo $i;?>">Add New Field &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></div>
            </section>
            <section class="facilitadors">
                <h2 class="projects-config"><?php _e("Facilitadors", 'eduhack-projects-widget');?></h2>
                <div class="facilitador-container">
                    <?php if($stored_meta['widget-facilitador_name'][0]!=''){
                        $i = 0;
                        $facilitador_name = unserialize($stored_meta['widget-facilitador_name'][0]);
                        $facilitador_name = unserialize($facilitador_name);
                        $facilitador_img = unserialize($stored_meta['widget-facilitador_img'][0]);
                        $facilitador_img = unserialize($facilitador_img);
                        $facilitador_school = unserialize($stored_meta['widget-facilitador_school'][0]);
                        $facilitador_school = unserialize($facilitador_school);

                        for ($i=0; $i<count($facilitador_name); $i++){
                            $this_image = wp_get_attachment_image_src( $facilitador_img[$i], 'thumbnail' );

                            $button_text = (empty($this_image)) ? __("Upload image", 'eduhack-projects-widget') : __("Change image", 'eduhack-projects-widget');
                            ?>
                            <div>
                                <label for="meta-text" class="facilitador-label"><?php _e( 'User Name', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="facilitador[name][]" value="<?php echo $facilitador_name[$i];?>">
                                <label for="meta-text" class="facilitador-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                                <input class="fac-image-url-<?php echo $i;?>" type="hidden" name="facilitador[image][]" value="<?php echo $facilitador_img[$i];?>" />
                                <input type="button" class="button upload-button button-<?php echo $i;?>" value="<?php echo $button_text;?>" data-buttonid="<?php echo $i;?>" data-att-image="fac-image-url-" data-img-src="fac-image-src-"/>
                                <img src="<?php echo $this_image[0]; ?>" class="facilitador-img fac-image-src-<?php echo $i;?>"/>
                                <label for="meta-text" class="facilitador-label"><?php _e( 'School', 'eduhack-projects-widget' )?></label>
                                <input type="text" name="facilitador[school][]" value="<?php echo $facilitador_school[$i];?>">
                                <a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        <?php }?>

                    <?php }else{?>
                        <div>
                            <label for="meta-text" class="facilitador-label"><?php _e( 'User Name', 'eduhack-projects-widget' )?></label>
                            <input type="text" name="facilitador[name][]">
                            <label for="meta-text" class="facilitador-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                            <input class="image-url-0" type="hidden" name="facilitador[image][]" />
                            <input type="button" class="button upload-button" value="Upload Image" data-buttonid="0" data-att-image="fac-image-url-" data-img-src="fac-image-src-"/>
                            <img src="" class="facilitador-img fac-image-src-0"/>
                            <label for="meta-text" class="facilitador-label"><?php _e( 'School', 'eduhack-projects-widget' )?></label>
                            <input type="text" name="facilitador[school][]">
                        </div>
                    <?php }?>
                </div>
                <div class="add_facilitador_field" data-count="<?php echo $i;?>">Add New Field &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></div>
            </section>
        </div>
        <div id="tabs-description">
            <section class="description">
                <h2 class="projects-config"><?php _e("Description", 'eduhack-projects-widget');?></h2>
                <?php
                $content = ( isset ( $stored_meta['widget-description'] ) ) ? $stored_meta['widget-description'][0] : '';
                $editor_id = 'description';

                wp_editor( $content, $editor_id );
                ?>

            </section>
            <section>
                <h2 class="projects-config"><?php _e("Tags", 'eduhack-projects-widget');?></h2>
                <?php

                //tags group 1
                $tags1_text = unserialize($stored_meta['widget-config_tags1'][0]);
                $tags1_text = unserialize($tags1_text);
                $tags1_color = unserialize($stored_meta['widget-config_color1'][0]);
                $tags1_color = unserialize($tags1_color);

                //tags group 1
                $tags2_text = unserialize($stored_meta['widget-config_tags2'][0]);
                $tags2_text = unserialize($tags2_text);
                $tags2_color = unserialize($stored_meta['widget-config_color2'][0]);
                $tags2_color = unserialize($tags2_color);

                $selected_tags1_text = unserialize($stored_meta['widget-tags1_text'][0]);
                $selected_tags1_text = unserialize($selected_tags1_text);
                $selected_tags1_color = unserialize($stored_meta['widget-tags1_color'][0]);
                $selected_tags1_color = unserialize($selected_tags1_color);

                $selected_tags2_text = unserialize($stored_meta['widget-tags2_text'][0]);
                $selected_tags2_text = unserialize($selected_tags2_text);
                $selected_tags2_color = unserialize($stored_meta['widget-tags2_color'][0]);
                $selected_tags2_color = unserialize($selected_tags2_color);

                ?>

                <?php if(!empty($tags1_text)){?>
                    <div class="tags1">
                        <div class="selected-tags1">
                            <?php if(!empty($selected_tags1_text)){
                                    for($i=0; $i<count($selected_tags1_text); $i++){?>
                                        <div class="choosen-tag">
                                            <span class="color-tag" style="background-color:#<?php echo $selected_tags1_color[$i];?>"></span>
                                            <input type="text" readonly name="selected_tags_text1[]" value="<?php echo $selected_tags1_text[$i];?>">
                                            <input type="hidden" name="selected_tags_color1[]" value="<?php echo $selected_tags1_color[$i];?>">
                                            <a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                        </div>
                                    <?php }?>
                            <?php }?>
                        </div>
                        <label for="meta-text" class="tags1-label"><?php _e( 'Etiquetes destacades', 'eduhack-projects-widget' )?></label>
                        <select>
                            <option value=""><?php _e( 'Select tags', 'eduhack-projects-widget' );?></option>
                        <?php for($i=0; $i<count($tags1_text); $i++){?>
                            <option value="<?php echo $i;?>" data-color="<?php echo $tags1_color[$i];?>"><?php echo $tags1_text[$i];?></option>
                        <?php }?>
                        </select>
                    </div>
                <?php }?>

                <?php if(!empty($tags2_text)){?>
                    <div class="tags2">
                        <div class="selected-tags2">
                            <?php if(!empty($selected_tags2_text)){
                                for($i=0; $i<count($selected_tags2_text); $i++){?>
                                    <div class="choosen-tag">
                                        <span class="color-tag" style="background-color:#<?php echo $selected_tags2_color[$i];?>"></span>
                                        <input type="text" readonly name="selected_tags_text2[]" value="<?php echo $selected_tags2_text[$i];?>">
                                        <input type="hidden" name="selected_tags_color2[]" value="<?php echo $selected_tags2_color[$i];?>">
                                        <a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </div>
                                <?php }?>
                            <?php }?>
                        </div>
                        <label for="meta-text" class="tags2-label"><?php _e( 'Etiquetes genèriques', 'eduhack-projects-widget' )?></label>
                        <select>
                            <option value=""><?php _e( 'Select tags', 'eduhack-projects-widget' );?></option>
                        <?php for($i=0; $i<count($tags2_text); $i++){?>
                            <option value="<?php echo $i;?>" data-color="<?php echo $tags2_color[$i];?>"><?php echo $tags2_text[$i];?></option>
                        <?php }?>
                        </select>
                    </div>
                <?php }?>

            </section>
            <section>
                <h2 class="projects-config"><?php _e("Buttons", 'eduhack-projects-widget');?></h2>
                <div class="buttons">
                    <textarea rows="10" cols="50" name="buttons" ><?php if ( isset ( $stored_meta['widget-buttons'] ) ) echo $stored_meta['widget-buttons'][0]; ?></textarea>
                </div>
            </section>
        </div>
        <div id="tabs-status">
            <section>
                <h2 class="projects-config"><?php _e("Status", 'eduhack-projects-widget');?></h2>
                <div class="status">
                    <?php for($s=1; $s<=4; $s++){
                        $checked = (in_array((string)$s, $status, true)) ? 'checked' : '';
                        ?>
                        <label for="meta-text" class="status-label <?php echo $s;?>"><?php _e( 'Fase', 'eduhack-projects-widget' )?> <?php echo $s;?></label>
                        <input type="checkbox" value="<?php echo $s;?>" name="status[]" <?php echo $checked;?> />
                    <?php }?>

                </div>
            </section>
        </div>
        <?php /*if ( current_user_can( 'manage_options' ) ) { ?>
        <div id="tabs-configuration">
            <section class="configuration-tags1">
                <h2 class="projects-config"><?php _e("Etiquetes destacades", 'eduhack-projects-widget');?></h2>
                <div class="config-tags1-container">
                    <?php if($stored_meta['widget-config_tags1'][0]!=''){
                        $config_tag_text = unserialize($stored_meta['widget-config_tags1'][0]);
                        $config_tag_text = unserialize($config_tag_text);
                        $config_tag_img = unserialize($stored_meta['widget-config_tag_img'][0]);
                        $config_tag_img = unserialize($config_tag_img);
                        //$config_tag_color = unserialize($stored_meta['widget-config_color1'][0]);
                        //$config_tag_color = unserialize($config_tag_color);

                        for ($i=0; $i<count($config_tag_text); $i++){
                            $this_image = wp_get_attachment_image_src( $config_tag_img[$i], 'thumbnail' );

                            $button_text = (empty($this_image)) ? __("Upload image", 'eduhack-projects-widget') : __("Change image", 'eduhack-projects-widget');

                            ?>
                            <div>
                                <input type="text" name="config1[tag-text][]" value="<?php echo $config_tag_text[$i];?>">
                                <label for="meta-text" class="team-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                                <input class="config-image-url-<?php echo $i;?>" type="hidden" name="config1[image][]" value="<?php echo $config_tag_img[$i];?>" />
                                <input type="button" class="button upload-button button-<?php echo $i;?>" value="<?php echo $button_text;?>" data-buttonid="<?php echo $i;?>" data-att-image="config-image-url-" data-img-src="config-image-src-"/>
                                <img src="<?php echo $this_image[0]; ?>" class="team-img config-image-src-<?php echo $i;?>"/>
                                <a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        <?php }?>

                    <?php }else{?>
                        <div>
                            <input type="text" name="config1[tag-text][]">
                            <label for="meta-text" class="team-label"><?php _e( 'Image', 'eduhack-projects-widget' )?></label>
                            <input class="config-image-url-<?php echo $i;?>" type="hidden" name="config1[image][]" value="<?php echo $team_img[$i];?>" />
                            <input type="button" class="button upload-button button-<?php echo $i;?>" value="<?php echo $button_text;?>" data-buttonid="<?php echo $i;?>" data-att-image="config-image-url-" data-img-src="config-image-src-"/>
                            <img src="<?php echo $this_image[0]; ?>" class="team-img config-image-src-<?php echo $i;?>"/>
                        </div>
                    <?php }?>
                </div>
                <div class="add_form_tag1">Add New Tag &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></div>
            </section>
            <section class="configuration-tags2">
                <h2 class="projects-config"><?php _e("Etiquetes genèriques", 'eduhack-projects-widget');?></h2>
                <div class="config-tags2-container">
                    <?php if($stored_meta['widget-config_tags2'][0]!=''){
                        $config_tag_text = unserialize($stored_meta['widget-config_tags2'][0]);
                        $config_tag_text = unserialize($config_tag_text);
                        $config_tag_color = unserialize($stored_meta['widget-config_color2'][0]);
                        $config_tag_color = unserialize($config_tag_color);

                        for ($i=0; $i<count($config_tag_text); $i++){?>
                            <div>
                                <input type="text" name="config2[tag-text][]" value="<?php echo $config_tag_text[$i];?>">
                                <label for="meta-text" class="config-tag-label"><?php _e( 'Color', 'eduhack-projects-widget' )?></label>
                                <input class="jscolor" value="<?php echo $config_tag_color[$i];?>" name="config2[tag-color][]">
                                <a href="#" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        <?php }?>

                    <?php }else{?>
                        <div>
                            <input type="text" name="config2[tag-text][]">
                            <label for="meta-text" class="config-tag-label"><?php _e( 'Color', 'eduhack-projects-widget' )?></label>
                            <input class="jscolor" value="" name="config2[tag-color][]">
                        </div>
                    <?php }?>
                </div>
                <div class="add_form_tag2">Add New Tag &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></div>
            </section>
            <section class="configuration-fase">
                <h2 class="projects-config"><?php _e("Current Status", 'eduhack-projects-widget');?></h2>
                <?php $config_current_fase = $stored_meta['widget-config_fase'][0];?>

                <select name="current-fase">
                    <?php for($i=1; $i<=12; $i++){
                            $selected = ($config_current_fase == $i)? 'selected' : '';
                        ?>
                        <option value="<?php echo $i;?>" <?php echo $selected;?>><?php _e( 'Fase', 'eduhack-projects-widget' )?> <?php echo $i;?></option>
                    <?php }?>
                </select>
            </section>
        </div>
        <?php } */?>
    </div>

    <?php
}

/**
 * Saves the custom meta input
 */
function projects_meta_save( $post_id ) {

    global $wpdb;
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'status' ] ) ) {
        update_post_meta( $post_id, 'widget-status', sanitize_text_field(serialize( $_POST[ 'status' ] )) );
    }
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'description' ] ) ) {
        update_post_meta( $post_id, 'widget-description',  $_POST[ 'description' ]  );
    }
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'buttons' ] ) ) {
        update_post_meta( $post_id, 'widget-buttons',  $_POST[ 'buttons' ]  );
    }
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'team' ] ) ) {
        update_post_meta($post_id, 'widget-team_name', sanitize_text_field(serialize($_POST['team']['name'])));
        update_post_meta($post_id, 'widget-team_school', sanitize_text_field(serialize($_POST['team']['school'])));
        update_post_meta($post_id, 'widget-team_img', sanitize_text_field(serialize($_POST['team']['image'])));

    }else{
        delete_post_meta($post_id, 'widget-team_name');
        delete_post_meta($post_id, 'widget-team_school');
        delete_post_meta($post_id, 'widget-team_img');
    }
    if( isset( $_POST[ 'facilitador' ] ) ) {
        update_post_meta($post_id, 'widget-facilitador_name', sanitize_text_field(serialize($_POST['facilitador']['name'])));
        update_post_meta($post_id, 'widget-facilitador_school', sanitize_text_field(serialize($_POST['facilitador']['school'])));
        update_post_meta($post_id, 'widget-facilitador_img', sanitize_text_field(serialize($_POST['facilitador']['image'])));

    }else{
        delete_post_meta($post_id, 'widget-facilitador_name');
        delete_post_meta($post_id, 'widget-facilitador_school');
        delete_post_meta($post_id, 'widget-facilitador_img');
    }
    if( isset( $_POST[ 'selected_tags_text1' ] ) ) {
        update_post_meta( $post_id, 'widget-tags1_text', sanitize_text_field(serialize( $_POST[ 'selected_tags_text1' ]) ) );
        update_post_meta( $post_id, 'widget-tags1_color', sanitize_text_field(serialize( $_POST[ 'selected_tags_color1' ]) ) );
    }else{
        delete_post_meta($post_id, 'widget-tags1_text');
        delete_post_meta($post_id, 'widget-tags1_color');
    }
    if( isset( $_POST[ 'selected_tags_text2' ] ) ) {
        update_post_meta( $post_id, 'widget-tags2_text', sanitize_text_field(serialize( $_POST[ 'selected_tags_text2' ]) ) );
        update_post_meta( $post_id, 'widget-tags2_color', sanitize_text_field(serialize( $_POST[ 'selected_tags_color2' ]) ) );
    }else{
        delete_post_meta($post_id, 'widget-tags2_text');
        delete_post_meta($post_id, 'widget-tags2_color');
    }

    if( isset( $_POST[ 'config1' ] ) ) {
        update_post_meta( $post_id, 'widget-config_tags1', sanitize_text_field(serialize( $_POST[ 'config1' ]['tag-text']) ) );
        update_post_meta($post_id, 'widget-config_tag_img', sanitize_text_field(serialize($_POST['config1']['image'])));

        //update_post_meta( $post_id, 'widget-config_color1', sanitize_text_field(serialize( $_POST[ 'config1' ]['tag-color']) ) );
    }else{
        delete_post_meta($post_id, 'widget-config_tags1');
        delete_post_meta($post_id, 'widget-config_tag_img');
        //delete_post_meta($post_id, 'widget-config_color1');
    }
    if( isset( $_POST[ 'config2' ] ) ) {
        update_post_meta( $post_id, 'widget-config_tags2', sanitize_text_field(serialize( $_POST[ 'config2' ]['tag-text']) ) );
        update_post_meta( $post_id, 'widget-config_color2', sanitize_text_field(serialize( $_POST[ 'config2' ]['tag-color']) ) );
    }else{
        delete_post_meta($post_id, 'widget-config_tags2');
        delete_post_meta($post_id, 'widget-config_color2');
    }
    if( isset( $_POST[ 'current-fase' ] ) ) {
        update_post_meta( $post_id, 'widget-config_fase',  $_POST[ 'current-fase' ]  );
    }

}
add_action( 'save_post', 'projects_meta_save' );

function makeFileSafe($filePath)
{
    $fP = @fopen($filePath,'r+');
    if (!$fP)
    {
        return "Could not read file";
    }
    $contents = fread($fP,filesize($filePath));
    $contents = strip_tags($contents);
    rewind($fP);
    fwrite($fP,$contents);
    fclose($fP);
    return $contents;
}

include( plugin_dir_path( __FILE__ ) . '/eduhack-projects-custom-widget.php');


function change_post_type_template()
{
    global $post;

    if ($post->post_type == 'cpt_project')
    {
        $single_template = plugin_dir_path( __FILE__ ) . 'single-cpt_project.php';
    }

    return $single_template;
}
add_filter( 'single_template', 'change_post_type_template' );

//include( plugin_dir_path( __FILE__ ) . '/single-cpt_project.php');



// Create WS link

// create custom plugin settings menu
add_action('admin_menu', 'eduhack_data_menu');

function eduhack_data_menu() {

    //create new top-level menu
    add_menu_page('Eduhack Data', 'Projects Data', 'administrator', __FILE__, 'eduhack_data_settings_page' , 'dashicons-migrate' );

    //call register settings function
    add_action( 'admin_init', 'eduhack_data_settings' );
}


function eduhack_data_settings() {
    //register our settings
    register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
    register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
    register_setting( 'my-cool-plugin-settings-group', 'option_etc' );
}

function eduhack_data_settings_page() {
    ?>
    <div class="wrap">
        <h1>Eduhack Projects Data</h1>
        <a href="<?php echo plugins_url('ws-eduhack-data.php', __FILE__);?>" target="_blank">Get data</a>
        <h4><?php echo plugins_url('ws-eduhack-data.php', __FILE__);?></h4>
    </div>
<?php }