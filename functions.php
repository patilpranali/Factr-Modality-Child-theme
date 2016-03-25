<?php
/**
 * Modality Child functions and definitions
 * Created and Edited by PRANALI PATIL
 * @package Modality Child
*/

//Enqueue Parent Stylesheet

function enqueue_modality_child_styles() {
  wp_enqueue_style( 'modality-parent', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'enqueue_modality_child_styles'); /*, PHP_INT_MAX);*/

/* ------------------------------------------------------------------------- *
 *  unslider.js is changed to fix the last slide of a slider not displaying on firefox for certain screen resolutions.
/* ------------------------------------------------------------------------- */

function unslider_script_fix()
{
    //use same handle as parent theme to override the js.
    wp_enqueue_script( 'unslider', dirname( get_bloginfo('stylesheet_url') ) . '/js/unslider.js', array( 'jquery' ),'', true);

}
add_action( 'wp_enqueue_scripts', 'unslider_script_fix' );


/* ------------------------------------------------------------------------- *
 *  modality_child_customize_register is new function to add a new custom control to modality theme customizer. The Donate button on slider should point to a custom URL entered by user in theme customizer to link to donation page.Below function adds new "Captions_button_url" input field to customizer.
/* ------------------------------------------------------------------------- */
function modality_child_customize_register($wp_customize){

  $wp_customize->add_setting('modality_theme_options[caption_button_url]', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',

    ));

    $wp_customize->add_control('caption_button_url', array(
        'label'      => __('Captions Button URL', 'modality'),
        'section'    => 'Slider Settings',
        'settings'   => 'modality_theme_options[caption_button_url]',
    ));
}
add_action( 'customize_register', 'modality_child_customize_register', 15);

/* ------------------------------------------------------------------------- *
 *  modality_child_slider replaces the original modality_slider function from parent. Line no. 51 added, line No.75 changed.
/* ------------------------------------------------------------------------- */
function modality_child_slider() {
global $post;
$modality_theme_options = modality_get_options( 'modality_theme_options' );
$slider_cat = $modality_theme_options['image_slider_cat'];
$num_of_slides = $modality_theme_options['slider_num'];
$button_text = $modality_theme_options['caption_button_text'];
$button_url = $modality_theme_options['caption_button_url'];  //Added by PRANALI PATIL: to output the custom URL

$modality_slider_query = new WP_Query(
	array(
		'posts_per_page' => $num_of_slides,
		'cat' 	=> $slider_cat
	)
);?>
<div class="clear"></div>
<div class="banner">
	<ul>
	<?php while ( $modality_slider_query->have_posts() ): $modality_slider_query->the_post(); ?>
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
		<?php if ($slider_cat !='') { ?>
			<li style="background: url(<?php echo esc_url($image[0]); ?>) 50% 0 no-repeat;">
		<?php } else { ?>
			<li style="background: url(<?php echo get_template_directory_uri() ?>/images/assets/slide1.jpg) 50% 0 no-repeat fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">
		<?php } ?>
		<?php if ($modality_theme_options['captions_on'] == '1') { ?>
			<div class="inner">
				<a class="post-title" href="<?php the_permalink() ?>"><h1><?php the_title(); ?></h1></a>
				<?php the_excerpt(); ?>
			</div>
			<?php if ($modality_theme_options['captions_button'] == '1') { ?>
				<a href="<?php echo $button_url ?>" class="btn" target="_blank"><?php echo $button_text ?></a>
			<?php }; ?>
		<?php }; ?>
		</li>
	<?php endwhile; wp_reset_query(); ?>
	</ul>
</div>
<div class="clear"></div>

<?php
}



?>
