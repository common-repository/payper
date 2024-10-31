<?php
/**
 * Plugin Name: Payper
 * Plugin URI: https://wordpress.org/plugins/payper
 * Description: Allows to integrate Payper in a Wordpress site.
 * Version: 0.1.0
 * Author: Payper
 * Author URI: https://www.bepayper.com
 */

defined('ABSPATH') || exit;

function_exists('get_plugin_data') || require_once ABSPATH . 'wp-admin/includes/plugin.php';
$payper_plugin = get_plugin_data(__FILE__, false, false);
define('PAYPER_VER', $payper_plugin['Version']);
define('PAYPER_FILE', __FILE__);
define('PAYPER_DIR', dirname(__FILE__));
define('PAYPER_URL',plugins_url( '', __FILE__ ));

if ( is_admin() ) {
	require_once PAYPER_DIR . '/includes/adminbox.php';
	require_once PAYPER_DIR . '/includes/settings.php';
}

function payper_wpcom_javascript() {

	$meta_value=get_post_meta(get_the_id(),'_payper_premium_meta_key');
    if ($meta_value[0]=="1"){

	    $options = get_option( 'wp-payper_options' );

	    if ($options){
		    $payper_token=$options['payper_field_token'];
//		    $payper_article_tag=$options['payper_field_article_tag'];
		    $payper_title_tag=$options['payper_field_title_tag'];
		    $payper_content_tag=$options['payper_field_content_tag'];
        }

	    $widget_url="https://cdn-pre.bepayper.com/index.js";
	    if (key_exists('payper_field_production_env',$options))
            $widget_url="https://widget.bepayper.com/index.js";

		?>
		<script>
            function payper_tagcontent(){

                var x = document.getElementsByTagName('article')[0];
                if (!x) {
                    var x = document.getElementsByTagName('main')[0];
                    if (!x) {
                        console.log("payper unable to tag article");
                        return false;
                    }
                }

                var b=document.querySelector('.<?php echo esc_attr($payper_content_tag) ?>');
                if (!b) {
                    console.log("payper unable to tag content");
                    return false;
                }

                var t=document.querySelector('.<?php echo esc_attr($payper_title_tag) ?>');
                if (!b) {
                    console.log("payper unable to tag title");
                    return false;
                }

                b.setAttribute('p-article-body','');
                t.setAttribute('p-article-headline','');
                x.setAttribute('p-article-id','wplocal-<?php echo(esc_attr(get_the_id()));?>');
                x.setAttribute('p-article-url','<?php echo(esc_url(get_permalink()));?>');
                console.log("payper tagged content");
            }

            payper_tagcontent();


		</script>

		<script id="payper-script-loader" data-nscript="afterInteractive">function loadScript(a){var b=document.getElementsByTagName("head")[0],c=document.createElement("script");
                c.type="text/javascript",c.src="<?php echo esc_url($widget_url) ?>",c.onreadystatechange=a,c.onload=a,b.appendChild(c)}loadScript(function(){
                console.log("payper loaded");
                window.payper.setupFrontend("<?php echo esc_attr($payper_token) ?>")
            });
		</script>
		<?php
    }
}
add_action('wp_footer', 'payper_wpcom_javascript');

function payper_add_tag_text_to_title( $title, $id = null ) {

	$option_values = get_option( 'wp-payper_options' );

    if (!key_exists('payper_field_home_tag_titles',$option_values)) return $title;

    $icon_html='<div style="display: inline-flex;align-self: center;">
                    <svg width="48" height="48" viewBox="0 0 48 48" style="height: 1em;width: 1em;fill: #a88938;top: .125em;position: relative;">
                       <circle cx="24" cy="24" r="12" stroke="#a88938" stroke-width="12" fill="none" />
                    </svg>
                </div>';

	$meta_value=get_post_meta(get_the_id(),'_payper_premium_meta_key');
	if ($meta_value && ($meta_value[0]=="1") && (! is_admin()) && ( is_home() )){
		return $icon_html.$title;
	} else {
		return $title;
	}
}
add_filter( 'the_title', 'payper_add_tag_text_to_title', 10, 2 );