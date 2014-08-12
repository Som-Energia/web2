<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  
 *
 */
?>
		<div class="comments-area" id="comments">
<?php if ( post_password_required() ) : ?>
			<p class="nopassword"><?php _fe( 'This post is password protected. Enter the password to view any comments.' ); ?></p>
		</div>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
			<h3 class="title widget-title" id="comments-title"><span><?php
			printf( __fe('%d comments' ), get_comments_number());
			?></span></h3>

			<?php
				//Loop through and list the comments.
				wp_list_comments( 
					array( 
						'callback' => 'a13_comment',
						'end-callback' => 'a13_comment_end',
						'style'=> 'div' 
					) );
			?>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __fe( 'Older Comments' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __fe( 'Newer Comments' ) ); ?></div>
			</div><!-- .navigation -->
	<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

		/* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 */
		if ( ! comments_open() ) :
?>
		<p class="nocomments"><?php _fe( 'Comments are closed.' ); ?></p>
	<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>
	<?php
		$commenter = wp_get_current_commenter();
	
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		
		
		$field_author = '';
		$field_author = '<p class="input-row"><label for="author">' . __fe( 'Name' ).( $req ? '<em class="star">*</em>' : '' ) . '</label>' .
			            '<input id="author" name="author" type="text" value="' . esc_attr(  $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
						
		$field_email = '<p class="input-row"><label for="email">' . __fe( 'Email' ).( $req ? '<em class="star">*</em>' : '' ) . '</label>'  .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
						
		$field_url = '<p class="input-row"><label for="url">' . __fe( 'Website' ) . '</label>'  .
			            '<input id="url" name="url" type="text" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30" /></p>';
		
		$fields =  array(
			'author' => $field_author,
			'email'  => $field_email,
			'url'    => $field_url,
		);
		
		$form_params = array(
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'        => '<p  class="input-row full"><label for="comment">' . __fe( 'Message' ).'<em class="star">*</em>' . '</label>' . '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'title_reply'          => '<span>'.__fe( 'Leave a reply' ).'</span>',
			'title_reply_to'       => '<span>'.__fe( 'Leave a replay to %s' ).'</span>',
			'comment_notes_after'  => '<span class="info">' . __fe('required' ) . '<em class="star">*</em></span>',
			'comment_notes_before' => '<div class="form-info" data-error-msg="'.esc_attr(__fe('Please correct form' )).'"></div>',
            'logged_in_as'         => '<div class="form-info" data-error-msg="'.esc_attr(__fe('Please correct form' )).'"></div><p class="logged-in-as">' . sprintf( __fe( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), esc_url(admin_url( 'profile.php' )), $user_identity, esc_url(wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )) ) . '</p>',
			'id_submit'            => 'comment-submit',
			'label_submit'         => __fe( 'Submit Comment' ),
			'cancel_reply_link'    => __fe( 'Cancel reply' ),
			
		);
		
		comment_form( $form_params ); 
	?>
</div> <!--#comments-->