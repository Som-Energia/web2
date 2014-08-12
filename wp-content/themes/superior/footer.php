<?php global $apollo13; ?>
		</div><!-- #mid -->
        <footer id="footer" class="glass">
	<div id="newslettersm">
		<div id="contentnewsletter">

<?php /*
			<div id="mc_embed_signup">
			<form action="//somenergia.us2.list-manage.com/subscribe/post?u=56d8bf2e8806722821a650fd1&amp;id=455a585b8d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

			<div class="mc-field-group_footer" style="color:#b7b7b7;font-size:12px;width: 150px;margin-top: 10px;">¿Quieres estar informado?</div>
			<div class="mc-field-group_footer"><input type="text" onclick="this.value=''" onfocus="this.value='Nombre *'" value="Nombre *" name="FNAME" class="required" id="mce-FNAME" width="125"></div>
			<div class="mc-field-group_footer"><input type="text" onclick="this.value=''" onfocus="this.value='Apellidos *'" value="Apellidos *" name="LNAME" class="required" id="mce-LNAME" width="125"></div>
			<div class="mc-field-group_footer"><input type="email" onclick="this.value=''" onfocus="this.value='Email *'" value="Email *" name="EMAIL" class="required email" id="mce-EMAIL" width="125"></div>
			<div class="mc-field-group_footer"><input type="submit" value="Suscripción" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
			
			<div id="mce-responses" class="clear">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
	
			</div>
    			<div style="position: absolute; left: -5000px;"><input type="text" name="b_56d8bf2e8806722821a650fd1_455a585b8d" tabindex="-1" value=""></div>
			</form>
			</div>

*/?>

		</div>
	</div>
	<div id="footersomenergia">
	<div id="xarxesfooter" class="peusomenergia">
	<ul>
	<li><a href="https://www.facebook.com/somenergia" target="_blank" class="facebookSE"></a></li>
	<li><a href="https://twitter.com/somenergia" target="_blank" class="twitterSE"></a></li>
	<li><a href="https://www.youtube.com/user/SomEnergia" target="_blank" class="youtubeSE"></a></li>
	<li><a href="http://blog.somenergia.coop/" target="_blank" class="rssSE"></a></li>
	</ul>
	</div>
	<div id="menucentral" class="peusomenergia">  <?php
                    if ( has_nav_menu( 'footer-menu' ) ){
                        //place for 1-4 links
                        wp_nav_menu( array(
                                'container'       => false,
                                'link_before'     => '',
                                'link_after'      => '',
                                'depth'           => -1,
                                'menu_class'      => 'footer-menu-somenergia',
                                'theme_location'  => 'footer-menu' )
                        );
                    }

                 
                    ?></div>
	<div id="menulegal" class="peusomenergia">
<?php
                    
                        wp_nav_menu( array(
                                'menu_class'      => 'menulegal-somenergia',
                                'theme_location'  => 'menulegal' )
                        );
                    
                   ?>
</div>
	</div>
            <div class="foot-widget-content">
                <?php
                //is there any widgets
                if( is_active_sidebar('footer-widget-area' )){
                    $the_sidebars = wp_get_sidebars_widgets();
                    $widgets_no = count( $the_sidebars['footer-widget-area'] );
                    //class for widgets
                    $_class = 'five-col';

                    if($widgets_no === 5){}
                    elseif($widgets_no < 4 || $widgets_no === 6){
                        $_class = 'three-col';
                    }
                    elseif($widgets_no % 4 === 0 || $widgets_no % 4 === 3){
                        $_class = 'four-col';
                    }

                    echo '<div class="foot-widgets clearfix '.$_class.'">';
                    dynamic_sidebar( 'footer-widget-area' );
                    echo '</div>';
                }
                ?>

                <div class="footer-items clearfix">          
                    <a href="#top" id="to-top" class="to-top fa fa-chevron-up"></a>
                </div>
            </div>
        </footer>
<?php
        /* Always have wp_footer() just before the closing </body>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to reference JavaScript files.
         */

        wp_footer();
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular-resource.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular-cookies.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular-sanitize.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.15/angular-route.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bower-angular-translate/2.0.1/angular-translate.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js" type="text/javascript"></script>

<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/app.js"></script>
<script src="/wp-content/themes/superior/js/debug.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/controllers/main.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/controllers/order.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/services/prepayment.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/services/api.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/services/ui.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/services/validator.js"></script>
<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/app/scripts/controllers/prepayment.js"></script>

<script src="http://rawgit.com/Som-Energia/new-api-webforms/master/wp/stats.js" type="text/javascript"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>

</body>
</html>