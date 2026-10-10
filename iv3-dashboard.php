<?php
/**
 * Plugin Name: iv3 Dashboard
 * Plugin URI:  https://iv3.com.br
 * Description: Substitui o painel padrao do WordPress por um dashboard moderno iv3.
 * Version:     1.9.1
 * Author:      iv3 - Interatividade Virtual
 * License:     MIT
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'IV3_DASH_VERSION', '1.9.1' );
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

/* ── Pedidos Woo dos últimos 30 dias: COUNT/SUM direto no banco ──
   Uma única query agregada (HPOS ou tabela de posts legada), sem carregar
   cada pedido como objeto PHP. */
function iv3_woo_order_stats() {
    global $wpdb;
    $since    = gmdate( 'Y-m-d H:i:s', strtotime( '-30 days' ) );
    $statuses = [ 'wc-completed', 'wc-processing' ];
    $in       = implode( ',', array_fill( 0, count( $statuses ), '%s' ) );
    $args     = array_merge( $statuses, [ $since ] );
    $hpos     = class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' )
                && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
    if ( $hpos ) {
        $table = $wpdb->prefix . 'wc_orders';
        $sql   = $wpdb->prepare(
            "SELECT COUNT(*) AS orders, COALESCE(SUM(total_amount),0) AS revenue
             FROM {$table}
             WHERE type = 'shop_order' AND status IN ($in) AND date_created_gmt >= %s",
            $args
        );
    } else {
        $sql = $wpdb->prepare(
            "SELECT COUNT(p.ID) AS orders, COALESCE(SUM(CAST(pm.meta_value AS DECIMAL(20,2))),0) AS revenue
             FROM {$wpdb->posts} p
             LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = '_order_total'
             WHERE p.post_type = 'shop_order' AND p.post_status IN ($in) AND p.post_date_gmt >= %s",
            $args
        );
    }
    $row = $wpdb->get_row( $sql );
    return [
        'orders'  => $row ? intval( $row->orders ) : 0,
        'revenue' => $row ? floatval( $row->revenue ) : 0,
    ];
}

/* ── AJAX: stats (só contagens baratas) ── */
function iv3_ajax_stats() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    $pages = wp_count_posts('page');
    $posts = wp_count_posts('post');
    $users = count_users();
    $products = $properties = 0;
    $has_woo = class_exists('WooCommerce');
    $has_estatik = post_type_exists('properties');
    if ( $has_estatik ) {
        $prop_counts = wp_count_posts('properties');
        $properties = intval($prop_counts->publish);
    }
    if ( $has_woo ) {
        $wcp = wp_count_posts('product');
        $products = intval($wcp->publish);
    }
    wp_send_json_success([
        'pages'      => intval($pages->publish),
        'posts'      => intval($posts->publish),
        'users'      => intval($users['total_users']),
        'products'   => $products,
        'has_woo'    => $has_woo,
        'properties' => $properties,
        'has_estatik'=> $has_estatik,
        'wp_version' => get_bloginfo('version'),
        'php_version'=> PHP_VERSION,
    ]);
}
add_action( 'wp_ajax_iv3_stats', 'iv3_ajax_stats' );

/* ── AJAX: pedidos/receita Woo (requisição separada, com cache de 15min) ── */
function iv3_ajax_woo_stats() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    $woo_stats = [ 'orders' => 0, 'revenue' => 0 ];
    if ( class_exists('WooCommerce') ) {
        $woo_stats = get_transient('iv3_dash_woo_stats');
        if ( false === $woo_stats ) {
            $woo_stats = iv3_woo_order_stats();
            set_transient('iv3_dash_woo_stats', $woo_stats, 15 * MINUTE_IN_SECONDS);
        }
    }
    wp_send_json_success([
        'orders'  => $woo_stats['orders'],
        'revenue' => number_format($woo_stats['revenue'],2,',','.'),
    ]);
}
add_action( 'wp_ajax_iv3_woo_stats', 'iv3_ajax_woo_stats' );

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

/* ── AJAX: imoveis recentes (Estatik) ── */
function iv3_ajax_properties() {
    check_ajax_referer( 'iv3_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( null, 403 );
    if ( ! post_type_exists('properties') ) { wp_send_json_success([]); return; }
    $items = get_posts(['numberposts'=>6,'post_type'=>'properties','post_status'=>'any','orderby'=>'modified','order'=>'DESC']);
    wp_send_json_success(array_map(function($p){
        return [
            'title' => html_entity_decode( get_the_title($p), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'status'=> get_post_status($p),
            'date'  => get_the_date('d/m/Y',$p),
            'link'  => admin_url('post.php?post=' . $p->ID . '&action=edit'),
        ];
    }, $items));
}
add_action( 'wp_ajax_iv3_properties', 'iv3_ajax_properties' );
