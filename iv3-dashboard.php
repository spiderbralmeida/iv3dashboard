<?php
/**
 * Plugin Name: iv3 Dashboard
 * Plugin URI:  https://iv3.com.br
 * Description: Substitui o painel padrao do WordPress por um dashboard moderno iv3.
 * Version:     1.8.1
 * Author:      iv3 - Interatividade Virtual
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'IV3_DASH_VERSION', '1.8.1' );
define( 'IV3_DASH_DIR', plugin_dir_path( __FILE__ ) );
define( 'IV3_DASH_URL', plugin_dir_url( __FILE__ ) );

/* ── Auto-update via GitHub Releases ── */
require IV3_DASH_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php';
\YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/spiderbralmeida/iv3dashboard/',
    __FILE__,
    'iv3-dashboard'
);

function iv3_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    $wp_meta_boxes['dashboard'] = [];
    $known = [
        'dashboard_right_now','dashboard_activity','dashboard_quick_press',
        'dashboard_recent_drafts','dashboard_primary','dashboard_secondary',
        'woocommerce_dashboard_status','woocommerce_dashboard_recent_reviews',
        'yoast_db_widget','wpseo-dashboard-overview','e-dashboard-overview',
    ];
    foreach ( $known as $w ) {
        remove_meta_box( $w, 'dashboard', 'normal' );
        remove_meta_box( $w, 'dashboard', 'side' );
        remove_meta_box( $w, 'dashboard', 'core' );
    }
}
add_action( 'wp_dashboard_setup', 'iv3_remove_dashboard_widgets', 99999 );

function iv3_dashboard_assets( $hook ) {
    if ( $hook !== 'index.php' ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;
    wp_enqueue_style( 'iv3-dash', IV3_DASH_URL . 'assets/dashboard.css', [], IV3_DASH_VERSION );
    wp_enqueue_script( 'iv3-dash', IV3_DASH_URL . 'assets/dashboard.js', [ 'jquery' ], IV3_DASH_VERSION, true );
    wp_localize_script( 'iv3-dash', 'iv3Data', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'iv3_nonce' ),
        'adminUrl' => admin_url(),
    ]);
}
add_action( 'admin_enqueue_scripts', 'iv3_dashboard_assets' );

function iv3_inject_dashboard() {
    if ( ! ( $s = get_current_screen() ) || $s->id !== 'dashboard' ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;
    include IV3_DASH_DIR . 'templates/dashboard.php';
}
add_action( 'admin_notices', 'iv3_inject_dashboard', 1 );

function iv3_head_css() {
    if ( ! ( $s = get_current_screen() ) || $s->id !== 'dashboard' ) return;
    echo '<style>
        #wpbody-content .wrap > h1,
        #dashboard-widgets-wrap,
        #screen-meta, #screen-meta-links,
        .wp-header-end { display:none!important; }
        #wpbody-content .wrap { padding-top:0!important; margin-top:0!important; }
        #wpbody { padding-top:0!important; }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded",function(){
        var w=document.getElementById("dashboard-widgets-wrap");
        if(w) w.style.display="none";
        var h=document.querySelector("#wpbody-content .wrap > h1");
        if(h) h.style.display="none";
    });
    </script>';
}
add_action( 'admin_head', 'iv3_head_css' );

/* ── Helpers ── */
function iv3_clean_title( $post ) {
    return html_entity_decode( get_the_title( $post ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}

/* ── AJAX: stats ── */
function iv3_ajax_stats() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    $pages = wp_count_posts('page');
    $posts = wp_count_posts('post');
    $users = count_users();
    $products = $orders = $revenue = 0;
    $has_woo = class_exists('WooCommerce');
    if ( $has_woo ) {
        $wcp = wp_count_posts('product');
        $products = intval($wcp->publish);
        $ords = wc_get_orders(['limit'=>-1,'status'=>['wc-completed','wc-processing'],'date_created'=>'>'.strtotime('-30 days')]);
        $orders = count($ords);
        foreach($ords as $o) $revenue += floatval($o->get_total());
    }
    wp_send_json_success([
        'pages'      => intval($pages->publish),
        'posts'      => intval($posts->publish),
        'users'      => intval($users['total_users']),
        'products'   => $products,
        'orders'     => $orders,
        'revenue'    => number_format($revenue,2,',','.'),
        'has_woo'    => $has_woo,
        'wp_version' => get_bloginfo('version'),
        'php_version'=> PHP_VERSION,
    ]);
}
add_action( 'wp_ajax_iv3_stats', 'iv3_ajax_stats' );

/* ── AJAX: posts recentes ── */
function iv3_ajax_posts() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    $items = get_posts(['numberposts'=>6,'post_status'=>'any','orderby'=>'date','order'=>'DESC']);
    wp_send_json_success(array_map(function($p){
        return [
            'title' => html_entity_decode( get_the_title($p), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'status'=> get_post_status($p),
            'date'  => get_the_date('d/m/Y',$p),
            'link'  => admin_url('post.php?post=' . $p->ID . '&action=edit'),
        ];
    }, $items));
}
add_action( 'wp_ajax_iv3_posts', 'iv3_ajax_posts' );

/* ── AJAX: paginas recentes ── */
function iv3_ajax_pages() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    $items = get_posts(['numberposts'=>6,'post_type'=>'page','post_status'=>'any','orderby'=>'modified','order'=>'DESC']);
    wp_send_json_success(array_map(function($p){
        return [
            'title' => html_entity_decode( get_the_title($p), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'status'=> get_post_status($p),
            'date'  => get_the_date('d/m/Y',$p),
            'link'  => admin_url('post.php?post=' . $p->ID . '&action=edit'),
        ];
    }, $items));
}
add_action( 'wp_ajax_iv3_pages', 'iv3_ajax_pages' );

/* ── AJAX: produtos recentes ── */
function iv3_ajax_products() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    if ( ! class_exists('WooCommerce') ) { wp_send_json_success([]); return; }
    $items = get_posts(['numberposts'=>6,'post_type'=>'product','post_status'=>'any','orderby'=>'modified','order'=>'DESC']);
    wp_send_json_success(array_map(function($p){
        return [
            'title' => html_entity_decode( get_the_title($p), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'status'=> get_post_status($p),
            'date'  => get_the_date('d/m/Y',$p),
            'link'  => admin_url('post.php?post=' . $p->ID . '&action=edit'),
        ];
    }, $items));
}
add_action( 'wp_ajax_iv3_products', 'iv3_ajax_products' );
