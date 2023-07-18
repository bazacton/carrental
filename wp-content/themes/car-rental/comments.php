<?php
/**
 * The template for displaying Comment form
 */ 
    global $cs_theme_options;
    if ( comments_open() ) {
        if ( post_password_required() ) return;
    }   
    if ( have_comments() ) : 
	?>
    <div class="col-md-12">
        <div id="cs-comments">		
            <div class="cs-section-title"><h2><?php echo comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></h2></div>
            <ul>
                <?php wp_list_comments( array( 'callback' => 'cs_comment' ) );    ?>
            </ul>
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                <div class="navigation">
                    <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( esc_html__( 'Older Comments', 'car-rental') ); ?></div>
                    <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( esc_html__( 'Newer Comments', 'car-rental') ); ?></div>
                </div> <!-- .navigation -->
            <?php endif; // check for comment navigation ?>        
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                <div class="navigation">
                    <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( esc_html__( 'Older Comments', 'car-rental') ); ?></div>
                    <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( esc_html__( 'Newer Comments', 'car-rental') ); ?></div>
                </div><!-- .navigation -->
            <?php endif; ?>
        </div>
    </div>
	<?php
    endif;
	if ( comments_open() ) {
	?>
		<div class="col-md-12">
        <div id="respond-comment" class="cs-classic-form cs_form_styling blog_form">
            <?php 
            global $post_id;
            $you_may_use = esc_html__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'car-rental');
            $must_login ='<a href="%s">logged in</a>'.esc_html__( 'You must be  to post a comment.', 'car-rental');
            $logged_in_as = esc_html__('Logged in as ', 'car-rental'). '<a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">'.esc_html__('Log out', 'car-rental').'</a>';
            $required_fields_mark = ' ' .esc_html__('Required fields are marked %s', 'car-rental');
            $required_text = sprintf($required_fields_mark , '<span class="required">*</span>' );
            $defaults = array( 'fields' => apply_filters( 'comment_form_default_fields', 
                array(
                    'notes' => '',                
                    'author' => '<p class="comment-form-author">
                    <input placeholder="Enter Name" id="author"  name="author" class="nameinput" type="text" value=""' .
                    esc_attr( $commenter['comment_author'] ) . ' tabindex="1">' .
                    '</p><!-- #form-section-author .form-section -->',                
                    'email'  => '<p class="comment-form-email">' .
                    '<input id="email" name="email" placeholder="'.esc_html__('Email Address', 'car-rental').'" class="emailinput" type="text"  value=""' . 
                    esc_attr(  $commenter['comment_author_email'] ) . ' size="30" tabindex="2">' .
                    '</p><!-- #form-section-email .form-section -->',                
                    'url'    => '<p class="comment-form-website">' .
                    '<input id="url" name="url" type="text" placeholder="'.esc_html__('Website', 'car-rental').'" class="websiteinput"  value="" size="30" tabindex="3">' .
                    '</p>' ) ),                
                    'comment_field' => '<p class="comment-form-comment">
                        <textarea id="comment_mes" placeholder="'.esc_html__('Enter Message', 'car-rental').'" name="comment"  class="commenttextarea" rows="55" cols="15"></textarea>' .
                    '</p>',                
                    'must_log_in' => '<span>' .  sprintf( $must_login,    wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</span>',
                    'logged_in_as' => '<span>' . sprintf( $logged_in_as, admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ).'</span>',
                    'comment_notes_before' => '',
                    'comment_notes_after' =>  '',
                    'class_form' => 'comment-form contact-form',
                    'id_form' => 'form-style',
                    'class_submit' => 'submit-btn cs-bgcolor',
                    'id_submit' => 'cs-bg-color',
                    'title_reply' => esc_html__( 'Leave us a comment', 'car-rental' ),
                    'title_reply_to' =>'<h2 class="cs-section-title">'.esc_html__( 'Leave us a comment', 'car-rental' ).'</h2>',
                    'cancel_reply_link' => esc_html__( 'Cancel reply', 'car-rental' ),
                    'label_submit' => esc_html__( 'Submit', 'car-rental' ),); 
                    comment_form($defaults, $post_id); 
                ?>
		</div>
        </div>

    <?php
	}
