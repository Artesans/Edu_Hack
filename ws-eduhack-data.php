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
//loop over each post
foreach($posts as $p){
    $stored_meta = get_post_meta( $p );
    print_r($stored_meta);
}


