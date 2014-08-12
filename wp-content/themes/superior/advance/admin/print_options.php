<?php

/**
 * Generates form of settings
 * @param $options: current page sttings
 * @param $opt_name: name of settings group
 */
function a13_print_options( &$options, $opt_name ){
    global $apollo13;
    ?>
<form method="post" action="">
    <?php
    $fieldset_open = false;
    $params = array('opt_name' => $opt_name);

    foreach( $options as $option ) {
        if ( $option['type'] == 'fieldset' ) {
            if ( $fieldset_open ) {
                echo '</div>
                                        </div>';
                ?>
                <div class="save-opts"><input type="submit" name="theme_updated" class="button-primary autowidth" value="<?php esc_attr_e(__be( 'Save Changes' )); ?>" /></div>
                <?php
            }

            $closed_class = '';
            $input_value = '0';
            if( isset($option['id']) ){
                $hidden_val = $apollo13->get_option( $opt_name, $option['id'] );
                if($hidden_val == 0){
                    $closed_class = ' closed';
                }
                $input_value = $hidden_val;
            }

            //after each filed set print save button
            echo '<div class="postbox' . $closed_class . '"' . (isset($option['id'])? (' id="'.$option['id'].'"') : '') . '>
                            <div class="fieldset-name sidebar-name">
                                <div class="sidebar-name-arrow"><br></div>
                                <h3><span>' . $option['name'] . '</span></h3>
                                ' . (isset($option['id'])? ('<input type="hidden" name="'. $option['id'] . '" value="'.$input_value.'" />') : '') . '
                            </div>
                            <div class="inside">';
            //help info
            if(isset($option['help'])){
                printf( '<strong class="help-info">' . __be('If you need help with these settings <a href="%s">check this topic</a> in documentation') . '</strong>', A13_DOCS_LINK . $option['help']);
            }

            $fieldset_open = true;
        }

        //checks for all normal options
        elseif( a13_print_form_controls($option, $params ) ){
            continue;
        }

        /* OPTION only*/
        elseif ( $option['type'] == 'social' ) {
            ?>
            <input id="<?php echo $option['id']; ?>" type="hidden" name="<?php echo $option['id']; ?>" value="_array" />
            <p class="desc" style="padding-left: 275px;"><?php echo $option['desc']; ?></p>
            <?php

            //sort firstly

            $socials_arr = $apollo13->get_option( $opt_name, $option['id']);
            foreach($option['options'] as $id => $name):
                $social_link = isset($socials_arr[ $id ]['value']) ? $socials_arr[ $id ]['value'] : '';
                $social_pos = isset($socials_arr[ $id ]['pos']) ? $socials_arr[ $id ]['pos'] : '';
                ?>
                <div class="text-input input-parent clearfix">
                    <label for="<?php echo $id; ?>" class="social_icon a13_soc-<?php echo $id; ?>"><?php echo $name['name']; ?></label>
                    <div class="input-desc">
                        <input id="<?php echo $id; ?>" type="text" size="36" name="<?php echo $id; ?>" value="<?php echo esc_attr($social_link); ?>" />
                        <input id="<?php echo $id; ?>_pos" type="hidden" class="vhidden" size="3" name="<?php echo $id; ?>_pos" value="<?php echo $social_pos; ?>" />
                    </div>
                </div>
                <?php
            endforeach;
        }
    }

    /* Close last options div */
    if ( $fieldset_open ) {
        echo '</div>
                                </div>';
    }
    ?>
    <div class="save-opts"><input type="submit" name="theme_updated" class="button-primary autowidth" value="<?php _be( 'Save Changes' ); ?>" /></div>
</form>
<?php
}


/**
 * Generates input, selects and other form controls
 * @param $option : currently processed option with all attributes
 * @param $params : params for meta type or option type
 * @param $is_meta : meta or option
 * @return bool true if some field was used, false other way
 */
function a13_print_form_controls($option, &$params, $is_meta = false){
    global $apollo13;

    static $switches = array();



    /* SPECIAL CASE TYPES. NEED TO BE BEFORE VALUE GETTING */
    if ( $option['type'] == 'switch-group' ) {
        $style_group = ' style="display: none;"';
        $switch_value = end($switches); //get last added switch

        //check if current group should be visible
        if(strlen($switch_value && $switch_value == $option['name'])){
            $style_group = '';
        }

        echo '<div class="switch-group" data-switch="'.$option['name'].'"'.$style_group.'>';
        return true;
    }

    elseif ( $option['type'] == 'switch-group-end' ) {
        echo '</div>';
        return true;
    }

    elseif ( $option['type'] == 'end-switch' ) {
        //remove last added switch
        array_pop($switches);
        echo '</div>';
        return true;
    }



    /* Extract some variables */
    $style = '';
    $switch = isset($option['switch']) ? ' switch-control' : '';

    if($is_meta){
        $value = $params['value'];
        $style = $params['style'];
    }
    //if run for theme options
    else{
        $value = $apollo13->get_option( $params['opt_name'], $option['id'] );
    }

    //check if this option is switch
    if ( isset( $option['switch'] ) && $option['switch'] == true ) {
        echo '<div class="switch">';
        //add to switches array
        array_push($switches, $value);
    }



    /* NORMAL TYPES */
    if ( $option['type'] == 'upload' ) {
        $upload_button_text = !empty($option['button_text'])? $option['button_text'] : __be( 'Upload' );
        $inp_class = '';
        if(isset($option['for_thumb']) && $option['for_thumb'] == true){
            $inp_class = ' class="for-thumb"';
        }

        $media_button_text = '';
        if(isset($option['media_button_text']) && strlen($option['media_button_text'])){
            $media_button_text = ' data-media-button-name="'.$option['media_button_text'].'"';
        }

        $media_type = '';
        if(isset($option['media_type']) && strlen($option['media_type'])){
            $media_type = ' data-media-type="'.$option['media_type'].'"';
        }
        ?>

    <div class="upload-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?>&nbsp;</label>
        <div class="input-desc">
            <input id="<?php echo $option['id']; ?>"<?php echo $inp_class; ?> type="text" size="36" name="<?php echo $option['id']; ?>" value="<?php echo stripslashes(esc_attr( $value )); ?>" />
            <input id="upload_<?php echo $option['id']; ?>" class="upload-image-button" type="button" value="<?php echo $upload_button_text ?>"<?php echo $media_button_text; ?><?php echo $media_type; ?> />
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'input' ) {
        $inp_class      = isset($option['input_class']) ? (' class="'.$option['input_class'].'"') : '';
        $placeholder    = isset($option['placeholder']) ? (' placeholder="'.$option['placeholder'].'"') : '';
        ?>
    <div class="text-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?>&nbsp;</label>
        <div class="input-desc">
            <input id="<?php echo $option['id']; ?>"<?php echo $inp_class.$placeholder; ?> type="text" size="36" name="<?php echo $option['id']; ?>" value="<?php echo stripslashes(esc_attr( $value )); ?>" />
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'hidden' ) {
        ?>
    <div class="hidden-input input-parent"<?php echo $style; ?>>
        <input id="<?php echo $option['id']; ?>" type="hidden" name="<?php echo $option['id']; ?>" value="<?php echo esc_attr($value); ?>" />
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'textarea' ) {
        ?>
    <div class="textarea-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?>&nbsp;</label>
        <div class="input-desc">
            <textarea rows="10" cols="20" class="large-text" id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>"><?php echo stripslashes(esc_textarea( $value )); ?></textarea>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'select' ) {
        $selected = $value;
        $selected_prop = ' selected="selected"';
        ?>
    <div class="select-input input-parent<?php echo $switch; ?>"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <select id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>">
                <?php
                foreach( $option['options'] as $html_value => $html_option ) {
                    echo '<option value="' . esc_attr($html_value) . '"' . ((string)$html_value == (string)$selected? $selected_prop : '') . '>' . $html_option . '</option>';
                }
                ?>
            </select>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'font' ) {
        $font_parts = explode(':', $value);
        $font_name = $font_parts[0];
        $selected = $font_name;
        $selected_prop = ' selected="selected"';
        $checked_prop = ' checked="checked"';
        //link to generate: https://www.googleapis.com/webfonts/v1/webfonts?
        $google_fonts = json_decode(file_get_contents( A13_TPL_ADV_DIR . '/inc/google-font-json' ));
        $sample_text = 'Sample text with <strong>some bold words</strong> and numbers 1 2 3 4 5 6 7 8 9 69 ;-)';
        $options = '';
        $variants = array();
        $variants_html = '';
        $subsets = array();
        $subsets_html = '';

        //prepare select with fonts
        //Normal fonts
        $options .= '<optgroup label="'.__be('Classic fonts').'">';
        foreach( $option['options'] as $html_value => $html_option ) {
            $options .= '<option class="classic-font" value="' . esc_attr($html_value) . '"' . ($html_value == $selected? $selected_prop : '') . '>' . $html_option . '</option>';
        }
        $options .= '</optgroup>';

        //Google fonts
        $options .= '<optgroup label="'.__be('Google fonts').'">';
        foreach( $google_fonts->items as $font ) {
            $options .= '<option value="' . esc_attr($font->family) . '"' . ($font->family == $selected? $selected_prop : '') . '>' . $font->family . '</option>';
            //save params of current font
            if($font->family == $font_name){
                $variants = $font->variants;
                $subsets = $font->subsets;
            }
        }
        $options .= '</optgroup>';

        //prepare variants of selected font
        if(sizeof($variants) > 0){
            //make array of selected variants
            $used_variants = isset($font_parts[1])? explode(',', $font_parts[1]) : array();

            foreach( $variants as $v ) {
                $variants_html .= '<label><input type="checkbox" name="variant" value="'.$v.'"' . (in_array($v, $used_variants)? $checked_prop : '') . ' />'.$v.'</label>'."\n";
            }
        }

        //prepare subsets of selected font
        if(sizeof($subsets) > 0){
            //make array of selected subsets
            $used_subsets = isset($font_parts[2])? explode(',', $font_parts[2]) : array();

            foreach( $subsets as $s ) {
                $subsets_html .= '<label><input type="checkbox" name="subset" value="'.$s.'"' . (in_array($s, $used_subsets)? $checked_prop : '') . ' />'.$s.'</label>'."\n";
            }
        }
//FOR TEST
//        var_dump($google_fonts->items);
//        $variants = array();
//        $subsets = array();
//        foreach($google_fonts->items as $key => $item){
//            if(!in_array('regular', $item->variants)){
//                echo $item->family;
//            }
//                $variants[sizeof($item->variants)] += 1;
//                $subsets[sizeof($item->subsets)] += 1;
//            foreach($item->variants as $variant){
//                $variants[$variant] += 1;
//            }
//            foreach($item->subsets as $subset){
//                $subsets[$subset] += 1;
//            }
//            if(sizeof($item->subsets) == 7) echo $item->family;
//        }
//        var_dump($variants, $subsets);

        ?>
    <div class="select-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">

            <input id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>" class="font-request" type="hidden" value="<?php echo $value; ?>" />
            <input class="sample-text" type="text" value="<?php echo esc_attr($sample_text); ?>" />
            <span class="sample-view" style="font-family: <?php echo $font_name; ?>;"><?php echo $sample_text; ?></span>
            <p class="desc"><?php _be('Double click on sample text to edit it. After edit double click on input to see preview again.'); ?></p>

            <select class="fonts-choose first-load">
                <?php echo $options; ?>
            </select>
            <div class="font-info">
                <div>
                    <h4><?php _be( 'Variants' ) ?></h4>
                    <div class="variants">
                        <?php echo $variants_html; ?>
                    </div>
                </div>
                <div>
                    <h4><?php _be( 'Subsets' ) ?></h4>
                    <div class="subsets">
                        <?php echo $subsets_html; ?>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'radio' ) {
        $selected = $value;
        ?>
    <div class="radio-input input-parent<?php echo $switch; ?>"<?php echo $style; ?>>
        <span class="label-like"><?php echo $option['name']; ?></span>
        <div class="input-desc">
            <?php
            foreach( $option['options'] as $html_value => $html_option ) {
                $selected_attr = '';
                if ( (string)$html_value == (string)$selected ){
                    $selected_attr = ' checked="checked"';
                }
                echo '<label><input type="radio" name="' . $option['id'] . '" value="' . esc_attr($html_value) . '"' . $selected_attr . ' />' . $html_option . '</label>';
            }
            ?>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'color' ) {
        ?>
    <div class="color-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <input id="<?php echo $option['id']; ?>" type="text" class="with-color" name="<?php echo $option['id']; ?>" value="<?php echo stripslashes(esc_attr( $value )); ?>" />
            <button class="transparent-value button-secondary"><?php _be( 'Insert transparent value' ); ?></button>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'slider' ) {
        $min = isset($option['min'])? $option['min'] : '';
        $max = isset($option['max'])? $option['max'] : '';
        ?>
    <div class="slider-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <input class="slider-dump" id="<?php echo $option['id']; ?>" type="text" name="<?php echo $option['id']; ?>" value="<?php echo stripslashes(esc_textarea( $value )); ?>" />
            <div class="slider-place" data-min="<?php echo $min; ?>" data-max="<?php echo $max; ?>" data-unit="<?php echo $option['unit']; ?>"></div>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'wp_dropdown_pages' ) {
        $selected = $value;
        ?>
    <div class="select-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <?php
            $wp_pages = wp_dropdown_pages( array(
                    'selected' => $selected,
                    'name' => $option['id'],
                    'show_option_none' => __be('Select page'),
                    'option_none_value' => '0',
                    'echo' => 0
            ) );
            if(strlen($wp_pages))
                echo $wp_pages;
            else
                _be('<span class="empty-type">There is no pages yet!</span>');
            ?>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'wp_dropdown_galleries' ) {
        $selected = $value;
        $selected_prop = ' selected="selected"';
        ?>
    <div class="select-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <?php
            $wp_query_params = array(
                'posts_per_page' => -1,
                'no_found_rows' => true,
                'post_type' => A13_CUSTOM_POST_TYPE_GALLERY,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
                'orderby' => 'date'
            );

            $r = new WP_Query($wp_query_params);

            if ($r->have_posts()) :

                echo '<select name="' . $option['id'] . '" id="' . $option['id'] . '">';

                if(isset($option['pre_options'])){
                    foreach( $option['pre_options'] as $html_value => $html_option ) {
                        echo '<option value="' . esc_attr($html_value) . '"' . ($html_value == $selected? $selected_prop : '') . '>' . $html_option . '</option>';
                    }
                }

                while ($r->have_posts()) : $r->the_post();
                    echo '<option value="' . get_the_ID() . '"' . (((string)get_the_ID() == (string)$selected)? $selected_prop : '') . '>' . get_the_title() . '</option>';
                endwhile;

                echo '</select>';

                // Reset the global $the_post as this query will have stomped on it
                wp_reset_postdata();

            else:
                _be('<span class="empty-type">There is no galleries yet!</span>');
            endif;
            ?>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    elseif ( $option['type'] == 'sidebars' ) {
        $placeholder    = isset($option['placeholder']) ? (' placeholder="'.$option['placeholder'].'"') : '';
        ?>
    <div class="text-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?>&nbsp;</label>
        <div class="input-desc">
            <input id="<?php echo $option['id']; ?>"<?php echo $placeholder; ?> type="text" size="36" name="<?php echo $option['id']; ?>" value="" />
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
        <?php
        $custom_sidebars = unserialize($value);
        $sidebars_count = count($custom_sidebars);
        if(is_array($custom_sidebars) && $sidebars_count > 0){
            echo '<h3>'.__be('Your current custom sidebars:').'</h3>';
            echo '<ol id="a13-custom-sidebars">';
            foreach($custom_sidebars as $sidebar){
                echo '<li><b>'.$sidebar['name'].'</b> <a href="#" id="'.$sidebar['id'].'">'.__be( 'Remove sidebar').'</a></li>';
            }
            echo '</ol>';
        }
        ?>
    </div>
    <?php
        return true;
    }


    /* Not used */
    elseif ( $option['type'] == 'dropdown_blog_categories' ) {
        $selected = $value;
        $selected_prop = ' selected="selected"';
        ?>
    <div class="select-input input-parent"<?php echo $style; ?>>
        <label for="<?php echo $option['id']; ?>"><?php echo $option['name']; ?></label>
        <div class="input-desc">
            <select id="<?php echo $option['id']; ?>" name="<?php echo $option['id']; ?>">

                <?php
                foreach( $option['pre_options'] as $html_value => $html_option ) {
                    echo '<option value="' . esc_attr($html_value) . '"' . ((string)$html_value == (string)$selected? $selected_prop : '') . '>' . $html_option . '</option>';
                }

                $terms = get_categories();
                if( count( $terms ) ){
                    echo '<optgroup label="' . __be( 'Your Categories' ) . '">';
                    foreach($terms as $term) {
                        echo '<option value="' . $term->slug . '"' . ($term->slug == $selected? $selected_prop : '') . '>' . $term->name . '</option>';
                    }
                    echo '</optgroup>';
                }
                ?>
            </select>
            <p class="desc"><?php echo $option['desc']; ?></p>
        </div>
    </div>
    <?php
        return true;
    }

    return false;
}