<?php
/**
 * Your Inspiration Themes
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if( !empty( $class ) )
    { $class = ' ' . $class; }

echo '<div class="socials' . $class . '">';
if ( ! empty( $title ) ) echo '<h2>' . $title . '</h2>';

$socials = array_map( 'trim', explode( ',', $socials ) );        
$socials_accepted = array( 'facebook', 'twitter', 'google', 'pinterest' , 'bookmark' );
                                                    
foreach ( $socials as $i => $social ) {
    if ( in_array( $social, $socials_accepted ) ) {

        $url = $script = $attrs = '';
        
        $title = urlencode( get_the_title() );
        $permalink = urlencode( get_permalink() );
        $excerpt = urlencode( get_the_excerpt() );
            
        if ( $social == 'facebook' ) {
            $url = apply_filters( 'yit_share_facebook', 'https://www.facebook.com/sharer.php?u=' . $permalink . '&t=' . $title . '' );
            
        } else if ( $social == 'twitter' ) {
            $url = apply_filters( 'yit_share_twitter', 'https://twitter.com/share?url=' . $permalink . '&text=' . $title . '' );
            
        } else if ( $social == 'google' ) {
            $url = apply_filters( 'yit_share_google', 'https://plusone.google.com/_/+1/confirm?hl=en&url=' . $permalink . '&title=' . $title . '' );
            
        } else if ( $social == 'pinterest' ) {
            $src = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
            $url = apply_filters( 'yit_share_pinterest', 'http://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $src[0] . '&description=' . $excerpt );
            $attrs = ' onclick="window.open(this.href); return false;';
               
        } else if ( $social == 'bookmark' ) {
            $url = get_permalink();
            $attrs = ' title="' . urldecode( $title ) . '"';
        }
        
        echo do_shortcode( '[social icon_type="' . $icon_type . '" size="small" type="' . $social . '" title="' . $title . '" href="' . $url . '"' . $attrs . ' target="_blank"]' );
        echo $script;
    }
}                         
echo '</div>';