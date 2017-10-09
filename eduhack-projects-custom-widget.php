<?php
/**
 * Created by PhpStorm.
 * User: mireiachaler
 * Date: 22/08/2017
 * Time: 12:48
 */

// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            __('Project Widget', 'wpb_widget_domain'),

// Widget description
            array( 'description' => __( 'Widget to show project information', 'wpb_widget_domain' ), )
        );
    }

// Creating widget front-end

    public function widget( $args, $instance ) {

        global $post;


            $title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
            echo $args['before_widget'];
            if ( ! empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
            $stored_meta = get_post_meta( $post->ID );

            ?>

            <!-- STATUS -->
            <section id="project-status" class="project-section" >
                <h3><?php _e("Status");?></h3>
                <div class="checkout-wrap">
                    <ul class="checkout-bar">
                        <?php
                        $status = unserialize($stored_meta['widget-status'][0]);
                        $status = unserialize($status);

                        $status_names = array(1=>"Empatitzar", 2=>"Definir", 3=>"Idear", 4=>"Prototipar");

                        for($s=1; $s<=4; $s++){
                            $checked = (in_array((string)$s, $status, true)) ? 'active' : 'next';
                            $current = ($s == $stored_meta['widget-config_fase'][0]) ? 'current' : '';
                            ?>
                            <li class="project-fase fase <?php echo $checked;?> <?php echo $current;?>"><span class="status-title"><?php echo $status_names[$s];?></span></li>
                        <?php }?>
                        <!--<div class="status-legend">
                            <p>Objectiu: Fase <?php echo $stored_meta['widget-config_fase'][0];?></p>
                        </div>-->
                    </ul>
                </div>
            </section>



            <!-- TAGS -->
            <section id="project-tags" class="project-section" >
                <h3><?php _e("Tags");?></h3>
                <?php
                $selected_tags1_text = unserialize($stored_meta['widget-tags1_text'][0]);
                $selected_tags1_text = unserialize($selected_tags1_text);
                $selected_tags1_img = unserialize($stored_meta['widget-config_tag_img'][0]);
                $selected_tags1_img = unserialize($selected_tags1_img);

                $selected_tags2_text = unserialize($stored_meta['widget-tags2_text'][0]);
                $selected_tags2_text = unserialize($selected_tags2_text);
                $selected_tags2_color = unserialize($stored_meta['widget-tags2_color'][0]);
                $selected_tags2_color = unserialize($selected_tags2_color);

                if(!empty($selected_tags1_text)){?>
                    <div class="block-tags">
                        <?php
                        for($i=0; $i<count($selected_tags1_text); $i++){
                            $this_image = wp_get_attachment_image_src( $selected_tags1_img[$i], array(100, 100) );
                            ?>
                            <div class="choosen-tag tag1">
                                <img class="tag-img" src="<?php echo $this_image[0]; ?>"/>
                                <span class="text-tag"><?php echo $selected_tags1_text[$i];?></span>
                            </div>
                        <?php }?>
                    </div>
                <?php }
                if(!empty($selected_tags2_text)){?>
                    <div class="block-tags">
                        <?php
                        for($i=0; $i<count($selected_tags2_text); $i++){?>
                            <div class="choosen-tag">
                                <span class="color-tag" style="background-color:#<?php echo $selected_tags2_color[$i];?>"></span>
                                <span class="text-tag"><?php echo $selected_tags2_text[$i];?></span>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
            </section>



            <!-- DESCRIPTION -->
            <section id="project-description" class="project-section" >
                <h3><?php _e("Description");?></h3>
                <div class="description"><?php echo $stored_meta['widget-description'][0]; ?></div>
                <span class="read-more read-button"><?php _e("Read More");?></span>
            </section>


        
            <!-- TEAM -->
            <section id="project-team" class="project-section" >
                <h3><?php _e("Team");?></h3>
            <?php
            if($stored_meta['widget-team_name'][0]!='') {
                $team_name = unserialize($stored_meta['widget-team_name'][0]);
                $team_name = unserialize($team_name);
                $team_school = unserialize($stored_meta['widget-team_school'][0]);
                $team_school = unserialize($team_school);
                $team_img = unserialize($stored_meta['widget-team_img'][0]);
                $team_img = unserialize($team_img);

                for ($i = 0; $i < count($team_name); $i++) {
                    $this_image = wp_get_attachment_image_src( $team_img[$i], array(100, 100) );
                    ?>
                    <div class="team-member">
                        <div class="column-left"><img class="member-img" src="<?php echo $this_image[0]; ?>"/></div>
                        <div class="column-right">
                            <div class="member-name"><?php echo $team_name[$i]; ?></div>
                            <div class="member-school"><em><?php echo $team_school[$i]; ?></em></div>
                        </div>
                    </div>
                <?php }
            }
            ?>

                <!-- FACILITADORS -->
                <?php
                if($stored_meta['widget-facilitador_name'][0]!='') {
                    $facilitador_name = unserialize($stored_meta['widget-facilitador_name'][0]);
                    $facilitador_name = unserialize($facilitador_name);
                    $facilitador_school = unserialize($stored_meta['widget-facilitador_school'][0]);
                    $facilitador_school = unserialize($facilitador_school);
                    $facilitador_img = unserialize($stored_meta['widget-facilitador_img'][0]);
                    $facilitador_img = unserialize($facilitador_img);

                    for ($i = 0; $i < count($facilitador_name); $i++) {
                        $this_image = wp_get_attachment_image_src( $facilitador_img[$i], array(100, 100) );
                        ?>
                        <div class="team-member facilitador">
                            <div class="column-left"><img class="member-img" src="<?php echo $this_image[0]; ?>"/></div>
                            <div class="column-right">
                                <div class="member-name"><?php echo $facilitador_name[$i]; ?></div>
                                <div ><?php _e("Facilitador",'eduhack-projects-widget' )?></div>
                                <div class="member-school"><em><?php echo $facilitador_school[$i]; ?></em></div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
            </section>



            <!-- BUTTONS -->
            <section id="project-buttons" class="project-section" >
                <?php
                $buttons = $stored_meta['widget-buttons'][0];
                $buttons = explode(";;", $buttons);

                foreach($buttons as $button){
                    $this_button = explode(',', $button);
                    ?>

                    <a href="<?php echo $this_button[1];?>" target="_blank" class="button"><?php echo $this_button[0];?></a>
                <?php }?>
            </section>


            <?php

            echo $args['after_widget'];



    }

// Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class wpb_widget ends here