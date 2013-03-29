<?php
$pingeb_meta_prefix = 'pingeb_';

$pingeb_meta_box = array(
    'id' => 'pingeb_post_meta_box',
    'title' => 'pingeb.org ',
    'page' => 'page',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'use pingeb.org meta data',
            'id' => $pingeb_meta_prefix . 'active',
            'type' => 'checkbox'
        ),
        array(
            'name' => 'Content type',
            'id' => $pingeb_meta_prefix . 'type',
            'type' => 'select',
            'options' => array('Music', 'Book', 'Image')
        ),
        array(
            'name' => 'Content Title',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'title',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Artists',
            'desc' => 'Who created this content?',
            'id' => $pingeb_meta_prefix . 'artist',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Epub Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'epub',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Mobi Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'mobi',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'PDF Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'pdf',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Image Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'image',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'MP3 File Url',
            'desc' => 'MP3 File Url',
            'id' => $pingeb_meta_prefix . 'mp3file',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'show MP3 download square',
            'id' => $pingeb_meta_prefix . 'mp3download',
            'type' => 'checkbox'
        ),
        array(
            'name' => 'Soundcloud page Url',
            'desc' => 'Soundcloud',
            'id' => $pingeb_meta_prefix . 'soundcloud',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Soundcloud Player Url',
            'desc' => 'Soundcloud',
            'id' => $pingeb_meta_prefix . 'soundcloudplayer',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Amazon MP3 Shop Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'amazonmp3',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Amazon Book Shop Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'amazonbook',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Bandcamp Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'bandcamp',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'iTinues Url',
            'desc' => '',
            'id' => $pingeb_meta_prefix . 'itunes',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Web',
            'desc' => 'Website',
            'id' => $pingeb_meta_prefix . 'web',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Facebook',
            'desc' => 'Facebook',
            'id' => $pingeb_meta_prefix . 'facebook',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Twitter',
            'desc' => 'Twitter',
            'id' => $pingeb_meta_prefix . 'twitter',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Spotify',
            'desc' => 'Spotify',
            'id' => $pingeb_meta_prefix . 'spotify',
            'type' => 'text',
            'std' => ''
        ), 
        array(
            'name' => 'Youtube',
            'desc' => 'Youtube',
            'id' => $pingeb_meta_prefix . 'youtube',
            'type' => 'text',
            'std' => ''
        ), 
        array(
            'name' => 'Vimeo',
            'desc' => 'Vimeo',
            'id' => $pingeb_meta_prefix . 'vimeo',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Flickr',
            'desc' => 'Flickr',
            'id' => $pingeb_meta_prefix . 'flickr',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Wikipedia',
            'desc' => 'Wikipedia',
            'id' => $pingeb_meta_prefix . 'wikipedia',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Help Page Url',
            'desc' => 'Help',
            'id' => $pingeb_meta_prefix . 'help',
            'type' => 'text',
            'std' => ''
        ) 
    )
);


add_action('admin_menu', 'pingeb_post_meta_box');


function pingeb_post_meta_box() {
    global $pingeb_meta_box;
    add_meta_box($pingeb_meta_box['id'], $pingeb_meta_box['title'], 'pingeb_post_meta_box_show', $pingeb_meta_box['page'], $pingeb_meta_box['context'], $pingeb_meta_box['priority']);
}

// Callback function to show fields in meta box
function pingeb_post_meta_box_show() {
    global $pingeb_meta_box, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="pingeb_post_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table">';
    foreach ($pingeb_meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />';
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />';
                break;
            case 'select':
                echo '<select onChange="javascript:pingebSetFieldVisibility()" name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }
    echo '</table>';
    
    //Java script to control fields
    echo "
    <script language ='JavaScript'>
    
        function pingebSetFieldVisibility(){
            var type = document.getElementById('pingeb_type').selectedIndex;
            
            var soundcloud = document.getElementById('pingeb_soundcloud');
            var soundcloudplayer = document.getElementById('pingeb_soundcloudplayer');
            var mp3 = document.getElementById('pingeb_mp3file');
            var mp3download = document.getElementById('pingeb_mp3download');
            var amazonbook = document.getElementById('pingeb_amazonbook');
            var amazonmp3 = document.getElementById('pingeb_amazonmp3');
            var bandcamp = document.getElementById('pingeb_bandcamp');
            var itunes = document.getElementById('pingeb_itunes');
            var spotify = document.getElementById('pingeb_spotify');
            var epub = document.getElementById('pingeb_epub');
            var mobi = document.getElementById('pingeb_mobi');
            var pdf = document.getElementById('pingeb_pdf');
            var image = document.getElementById('pingeb_image');
            
            if(type == 0){ //music
                soundcloud.parentNode.parentNode.style.display = '';
                soundcloudplayer.parentNode.parentNode.style.display = '';
                mp3.parentNode.parentNode.style.display = '';
                mp3download.parentNode.parentNode.style.display = '';
                amazonmp3.parentNode.parentNode.style.display = '';
                amazonbook.parentNode.parentNode.style.display = 'none';
                bandcamp.parentNode.parentNode.style.display = '';
                itunes.parentNode.parentNode.style.display = '';
                spotify.parentNode.parentNode.style.display = '';
                epub.parentNode.parentNode.style.display = 'none';
                mobi.parentNode.parentNode.style.display = 'none';
                pdf.parentNode.parentNode.style.display = 'none';
                image.parentNode.parentNode.style.display = 'none';
            }
            
            if(type == 1){ //book
                soundcloud.parentNode.parentNode.style.display = 'none';
                soundcloudplayer.parentNode.parentNode.style.display = 'none';
                mp3.parentNode.parentNode.style.display = 'none';
                mp3download.parentNode.parentNode.style.display = 'none';
                amazonmp3.parentNode.parentNode.style.display = 'none';
                amazonbook.parentNode.parentNode.style.display = '';
                bandcamp.parentNode.parentNode.style.display = 'none';
                itunes.parentNode.parentNode.style.display = 'none';
                spotify.parentNode.parentNode.style.display = 'none';
                epub.parentNode.parentNode.style.display = '';
                mobi.parentNode.parentNode.style.display = '';
                pdf.parentNode.parentNode.style.display = '';
                image.parentNode.parentNode.style.display = 'none';
            }
            
            if(type == 2){ //image
                soundcloud.parentNode.parentNode.style.display = 'none';
                soundcloudplayer.parentNode.parentNode.style.display = 'none';
                mp3.parentNode.parentNode.style.display = 'none';
                mp3download.parentNode.parentNode.style.display = 'none';
                amazonmp3.parentNode.parentNode.style.display = 'none';
                amazonbook.parentNode.parentNode.style.display = 'none';
                bandcamp.parentNode.parentNode.style.display = 'none';
                itunes.parentNode.parentNode.style.display = 'none';
                spotify.parentNode.parentNode.style.display = 'none';
                epub.parentNode.parentNode.style.display = 'none';
                mobi.parentNode.parentNode.style.display = 'none';
                pdf.parentNode.parentNode.style.display = 'none';
                image.parentNode.parentNode.style.display = '';
            }
        }
    
        pingebSetFieldVisibility();
    </script>
    ";
}


add_action('save_post', 'pingeb_save_meta_data');
// Save data from meta box
function pingeb_save_meta_data($post_id) {
    global $pingeb_meta_box;
    
    if (!wp_verify_nonce($_POST['pingeb_post_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($pingeb_meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

add_filter('the_content', 'pingeb_show_meta');
function pingeb_show_meta($content) {
    global $pingeb_meta_box, $post;
    
    if(get_post_meta($post->ID, 'pingeb_active', true) == "on"){
        
        $type = get_post_meta($post->ID, 'pingeb_type', true);
        $title = get_post_meta($post->ID, 'pingeb_title', true);
        $artist = get_post_meta($post->ID, 'pingeb_artist', true);
        $mp3download = get_post_meta($post->ID, 'pingeb_mp3download', true);
        
        $divStart = "<div style='width:50%;float:left;padding-left:4px;padding-top:4px;padding-right:4px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;'>";
        $divEnd = "</div>";
        
        $media = "<br />";
        
        $squares = "<br /><div style='overflow:auto;width:100%;'>";
        
        foreach ($pingeb_meta_box['fields'] as $field) {
            $val = trim(get_post_meta($post->ID, $field['id'], true));
            
            if($val != ""){
                if($field['id'] == 'pingeb_web'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Official Website' src='" . plugins_url("pingeborg/img/squares/07.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_facebook'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Like at Facebook' src='" . plugins_url("pingeborg/img/squares/05.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_twitter'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Follow on Twitter' src='" . plugins_url("pingeborg/img/squares/06.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_youtube'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Watch on Youtube' src='" . plugins_url("pingeborg/img/squares/13.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_soundcloud'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Listen on Soundclound' src='" . plugins_url("pingeborg/img/squares/14.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                

                if($field['id'] == 'pingeb_flickr'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Follow on Twitter' src='" . plugins_url("pingeborg/img/squares/17.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_vimeo'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Watch on Vimeo' src='" . plugins_url("pingeborg/img/squares/18.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_wikipedia'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Wikipedia' src='" . plugins_url("pingeborg/img/squares/16.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($field['id'] == 'pingeb_help'){
                    $squares .= $divStart;
                    $squares .= "<a href='" . $val . "' target='_blank'>";
                    $squares .= "<img alt='Wikipedia' src='" . plugins_url("pingeborg/img/squares/03.png") . "' style='width:100%' /></a>";
                    $squares .= $divEnd;
                }
                
                if($type == "Music"){
                    if($field['id'] == 'pingeb_amazonmp3'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Music at Amazon MP3' src='" . plugins_url("pingeborg/img/squares/08.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_bandcamp'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Music at Bandcamp' src='" . plugins_url("pingeborg/img/squares/11.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_itunes'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Music at iTunes' src='" . plugins_url("pingeborg/img/squares/10.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_spotify'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Listen on Spotify' src='" . plugins_url("pingeborg/img/squares/12.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_mp3file'){
                        $media .= "<div style='width:100%;padding:5px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;'>";
                        $media .= "<p><b>". $title . "</b> - ";
                        $media .= "<i>". $artist . "</i><br />";
                        $media .= "<audio controls>";
                        $media .= "<source src='" . $val . "' type='audio/mpeg'>";
                        $media .= "Your browser does not support the audio element.";
                        $media .= "</audio>";
                        $media .= "</p></div>";
                        
                        if($mp3download = "on"){
                            $squares .= $divStart;
                            $squares .= "<a href='" . $val . "' target='_blank'>";
                            $squares .= "<img alt='Listen on Spotify' src='" . plugins_url("pingeborg/img/squares/04.png") . "' style='width:100%' /></a>";
                            $squares .= $divEnd;
                        }
                    }
                    
                    if($field['id'] == 'pingeb_soundcloudplayer'){
                        $media .= "<div style='width:100%;padding:5px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;'>";
                        $media .= "<iframe src='" . $val . "' height='166' width='100%' frameborder='no' scrolling='no'></iframe>";
                        $media .= "</div>";
                    }
                }
                
                if($type == "Book"){
                    if($field['id'] == 'pingeb_epub'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Download Ebook as Epub' src='" . plugins_url("pingeborg/img/squares/01.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_mobi'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Download Ebook as Mobi' src='" . plugins_url("pingeborg/img/squares/02.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_pdf'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Download Ebook as PDF' src='" . plugins_url("pingeborg/img/squares/15.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                    
                    if($field['id'] == 'pingeb_amazonbook'){
                        $squares .= $divStart;
                        $squares .= "<a href='" . $val . "' target='_blank'>";
                        $squares .= "<img alt='Book at Amazon' src='" . plugins_url("pingeborg/img/squares/09.png") . "' style='width:100%' /></a>";
                        $squares .= $divEnd;
                    }
                }
                
                if($type == "Image"){
                    if($field['id'] == 'pingeb_image'){
                        $media .= "<div style='width:100%;padding:5px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;'>";
                        $media .= "<p><b>". $title . "</b> - ";
                        $media .= "<i>". $artist . "</i><br />";
                        $media .= "<a href='" . $val . "' target='_blank'>";
                        $media .= "<img alt='Download Ebook as Epub' src='" . $val . "' style='width:100%' /></a>";
                        $media .= "</p></div>";
                    }
                }
            }
        }
        
        $squares .= "</div>";
        
        return $content . $media . $squares;
    } else {
        return $content;
    }
}

?>