<?php
/**
 * Created by PhpStorm.
 * User: mireiachaler
 * Date: 26/09/2017
 * Time: 17:32
 */

include '../../../wp-load.php';

$posts = get_posts(array(
        'post_type'   => 'cpt_project',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    )
);

$result = array();

//loop over each post
foreach($posts as $p){
    $stored_meta = get_post_meta( $p );

    $team_users = unserialize($stored_meta["widget-team_name"][0]);
    $team_users = unserialize($team_users);
    $result["team_name"] = $team_users;
    $team_images = unserialize($stored_meta["widget-team_img"][0]);
    $team_images = unserialize($team_images);
    foreach($team_images as $user_img){
        $image = wp_get_attachment_image_src( $user_img);
        $result["team_img"][] = $image[0];
    }

    $team_school = unserialize($stored_meta["widget-team_school"][0]);
    $team_school = unserialize($team_school);
    $result["team_school"] = $team_school;

    $facilitador_users = unserialize($stored_meta["widget-facilitador_name"][0]);
    $facilitador_users = unserialize($facilitador_users);
    $result["facilitador_name"] = $facilitador_users;

    $team_images = unserialize($stored_meta["widget-facilitador_img"][0]);
    $team_images = unserialize($team_images);
    foreach($facilitador_images as $facilitador_img){
        $image = wp_get_attachment_image_src( $facilitador_img);
        $result["facilitador_img"][] = $image[0];
    }

    $facilitador_school = unserialize($stored_meta["widget-facilitador_school"][0]);
    $facilitador_school = unserialize($facilitador_school);
    $result["facilitador_school"] = $facilitador_school;

    $result["buttons"] = $stored_meta["widget-buttons"][0];
    $status = unserialize($stored_meta["widget-status"][0]);
    $status = unserialize($status);
    $result["status"] = $status;

}

echo json_encode($result);

