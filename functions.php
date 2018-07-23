<?php
add_theme_support('post-thumbnails', array('post'));
add_theme_support('menus');

// Cors対応
add_action('rest_api_init', 'my_customize_rest_cors', 15);

function my_customize_rest_cors()
{
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        header('Access-Control-Allow-Origin: *');
        return $value;
    });
}


// 記事一覧のID取得
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/post_all_id', array(
        'methods' => 'GET',
        'callback' => 'post_all_id',
    ));
});

function post_all_id()
{

    $contents = array();
    $myQuery = new WP_Query();
    $param = array(
        'posts_per_page' => -1,
        'order' => 'DESC'
    );
    $myQuery->query($param);
    if ($myQuery->have_posts()):
        while ($myQuery->have_posts()) : $myQuery->the_post();
            $ID = get_the_ID();
            array_push($contents, array(
                "id" => $ID
            ));
        endwhile;
    endif;
    return $contents;
}

// 固定ページ一覧のID取得
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/page_all_slug', array(
        'methods' => 'GET',
        'callback' => 'page_all_slug',
    ));
});

function page_all_slug()
{

    $contents = array();
    $myQuery = new WP_Query();
    $param = array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'order' => 'DESC'
    );
    $myQuery->query($param);
    if ($myQuery->have_posts()):
        while ($myQuery->have_posts()) : $myQuery->the_post();
            $ID = get_the_ID();
            $SLUG = get_page_uri($ID);
            array_push($contents, array(
                "id" => $ID,
                "slug" => $SLUG
            ));
        endwhile;
    endif;
    return $contents;
}

?>
