<?php
/*
    Plugin Name: Joet Post Information
    Description: A plugin to display post information.
    Version: 1.0
    Author: Theodore Mentis
    Author URI: https://www.linkedin.com/in/thmentis/
*/

class JoetPluginPostStatistics{
    function __construct(){
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
    }

    function ifWrap($content){
        if (is_main_query() AND is_single() AND (get_option('joet_wordcount', 1) OR get_option('joet_charcount', 1) OR get_option('joet_readtime', 1))) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content){
        $html = '<h3>' . esc_html(get_option('joet_headline', 'Post Information')) . '</h3><p>';

        // get word count once, for wordcount and read time.
        if (get_option('joet_wordcount', '1') OR get_option('joet_readtime', '1')){
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option('joet_wordcount', '1' )) {
            $html .= 'This post has ' . $wordCount . ' words.<br>';
        }

        if (get_option('joet_charcount', '1' )) {
            $html .= 'This post has ' . strlen(strip_tags($wordCount)) . ' characters.<br>';
        }

        if (get_option('joet_readtime', '1' )) {
            $html .= 'This post will take about ' . round($wordCount/225) . ' minute(s) to read.<br>';
        }

        $html .= '</p>';

        if (get_option('joet_location', '0' ) == '0') {
            return $html . $content;
        }
        return $content . $html;
    }

    function settings(){
        add_settings_section('joet_first_section', null, null, 'joet-settings-page');

        add_settings_field('joet_location', 'Display Location', array($this, 'locationHTML'), 'joet-settings-page','joet_first_section');
        register_setting('joetplugin', 'joet_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

        add_settings_field('joet_headline', 'Headline Text', array($this, 'headlineHTML'), 'joet-settings-page','joet_first_section');
        register_setting('joetplugin', 'joet_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        add_settings_field('joet_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'joet-settings-page','joet_first_section', array('theName' => 'joet_wordcount'));
        register_setting('joetplugin', 'joet_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('joet_charcount', 'Characters Count', array($this, 'checkboxHTML'), 'joet-settings-page','joet_first_section', array('theName' => 'joet_charcount'));
        register_setting('joetplugin', 'joet_charcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('joet_readtime', 'Read Time', array($this, 'checkboxHTML'), 'joet-settings-page','joet_first_section', array('theName' => 'joet_readtime'));
        register_setting('joetplugin', 'joet_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    function sanitizeLocation($input) {
        if ($input != '0' AND $input != '1'){
            add_settings_error('joet_location', 'joet_location_error', 'Display location must be either the beggining or the end of the post.');
            return get_option('joet_location');
        }
        return $input;
    }

    function locationHTML() { ?>
        <select name="joet_location">
            <option value="0" <?php selected(get_option('joet_location'),'0') ?>>Beggining of Post</option>
            <option value="1" <?php selected(get_option('joet_location'),'1') ?>>End of Post</option>
        </select>
    <?php }

    function headlineHTML() { ?>
        <input type="text" name="joet_headline" value="<?php echo esc_attr(get_option('joet_headline')) ?>">
    <?php }

    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1" <?php checked(get_option($args['theName'], '1')) ?>>        
    <?php }

    function adminPage() {
        add_options_page('Joet Plugin Settings','Joet Settings', 'manage_options','joet-settings-page',array($this, 'joetHTML'));
    }

    function joetHTML(){ ?>
        <div class="wrap">
            <h1> JOET Settings</h1>
            <form action="options.php" method="POST">  
            <?php
                settings_fields('joetplugin');
                do_settings_sections('joet-settings-page');
                submit_button();
            ?>
            </form>
        </div>
    <?php }
}
$joetPluginPostStatistics = new JoetPluginPostStatistics();
