<?php

/*
 * Administration Options Class For Sociable 2
 */

class sociable_Admin_Options{
    
    /**
     * A Function To Hook To Admin Init.
     */
    function init(){        
        
        //Register Settings
		//echo dirname( plugin_basename( __FILE__ ) );
		//load_plugin_textdomain( 'sociable', false, dirname( plugin_basename( __FILE__ ) ) );
        register_setting( 'sociable_options_group' , 'sociable_options' );
        
        //Add The Settings Sections
        add_settings_section( 'sociable_locations', __( 'Locations' ),  array( 'sociable_Admin_Options' , 'location_options_callback' )  , 'sociable_options' );
        
        add_settings_section( 'sociable_options', __( 'General Options' ),  array( 'sociable_Admin_Options' , 'general_options_callback' )  , 'sociable_options' );
        
        //Add All The Settings Fields
        //self::add_settings_fields();
        
        
    }
    
    /**
     * Add The Menu Pages To The Administration Options
     */
    function add_menu_pages(){
        global $sociable_post_types;
        
        $page = add_options_page( __( 'Sociable Options' ), __( 'Sociable' ), 'manage_options', 'sociable_options' , array( 'sociable_Admin_Options' , 'Create_Options_Page' ) );

        //Add CSS And Javascript Specific To This Options Pages
        add_action( 'admin_print_styles-' . $page , array( 'sociable_Admin_Options' , 'enqueue_styles' ) );
        add_action( 'admin_print_scripts-' . $page , array( 'sociable_Admin_Options' , 'enqueue_scripts' ) ); 
        
        if( isset( $_POST['sociable_reset'] ) ){
            check_admin_referer( 'sociable-reset' );
            
            sociable_reset();
            wp_redirect( $_SERVER['HTTP_REFERER' ] ); 
        }  
        
        if( isset( $_POST['sociable_remove'] ) ){
            check_admin_referer( 'deactivate-sociable' );
            
            sociable_2_remove();
            //wp_redirect( $_SERVER['HTTP_REFERER' ] ); 
        } 
        
        /*
         * We can create The Meta Boxes Here
         */
        foreach( $sociable_post_types as $type => $data ){
            self::add_meta_box( $type );
        }
        //Also on posts and pages
        self::add_meta_box( 'post' );
        self::add_meta_box( 'page' );
        
    }
    
    /*
     * Function to Enqueue The Styles For The Options Page
     */
    function enqueue_styles(){
	 wp_enqueue_style( 'style-admin-css', SOCIABLE_HTTP_PATH . 'css/style-admin.css' );
        wp_enqueue_style( 'sociable-admin-css', SOCIABLE_HTTP_PATH . 'css/sociable-admin.css' );
        wp_enqueue_style( 'sociablecss' , SOCIABLE_HTTP_PATH . 'css/sociable.css' );
    }
    
    /*
     * Function To Enqueue The Scripts For The Options Page
     */
    function enqueue_scripts(){
        wp_enqueue_script('jquery'); 
        wp_enqueue_script('jquery-ui-core',false,array('jquery')); 
        wp_enqueue_script('jquery-ui-sortable',false,array('jquery','jquery-ui-core'));
        wp_enqueue_script( 'sociable-admin-js', SOCIABLE_HTTP_PATH . 'js/sociable-admin.js' , array( 'jquery','jquery-ui-core' , 'jquery-ui-sortable' ) );
		wp_enqueue_script( 'admin-fn-js', SOCIABLE_HTTP_PATH . 'js/admin-fn.js' , array( 'jquery','jquery-ui-core' , 'jquery-ui-sortable' ) );
		
    }
    
    
    
    /*
     * Function To Add The Settings Fields.
     */

    function do_site_selection_list(){

        global $sociable_options;
        
        $sociable_known_sites = get_option( 'sociable_known_sites' );

        /*
         * Sort The List Based On The Active Sites So That They Display Correctly.
         */
        $active_sites = isset( $sociable_options['active_sites'] ) && is_array( $sociable_options['active_sites'] )  ? $sociable_options['active_sites'] : array() ;
        
        //Start Blank
        $active = Array(); 
        
        //Disabled Untill Proven Active
	$disabled = $sociable_known_sites;
        
        //Loop Through The Active Sites, sorting into 2 arrays
	foreach( $active_sites as $sitename => $value ) {
		$active[$sitename] = $disabled[$sitename];
		unset( $disabled[$sitename] );
	}
        
	uksort($disabled, "strnatcasecmp");
        
        $sites = array_merge( $active, $disabled );
        
        $imagepath = isset( $sociable_options['sociable_imagedir'] ) ? $sociable_options['sociable_imagedir'] : '' ;
        
        if ($imagepath == "") {
                $imagepath = trailingslashit( SOCIABLE_HTTP_PATH ) . 'images/';
        } else {		
                $imagepath .= trailingslashit( $imagepath );
        }
        
        $out ='<ul id="sociable_site_list" >' ;
        $io = 0;
        foreach( $sites as $sitename => $site ){
				
			
            //Set Checked And Active If Relevant
            if( array_key_exists( $sitename, $active_sites ) ){
                $checked = 'checked="checked"';
                $active = 'active';
            } else {
                $checked = '';
                $active = 'inactive';
            }
            if ( $sitename != "More"){
				if (isset($site["counter"])){
					//$image = "<img src='".SOCIABLE_HTTP_PATH."images/".$site["favicon"]."'>";
					$image = $site["url"];
				}else{
					$image = _get_sociable_image( $site, '' );
				}
			}else{
			$image = "<img src='".SOCIABLE_HTTP_PATH."images/more.png'>";
			}
            
//            if ( ! isset( $site['spriteCoordinates']) || isset( $sociable_options['sociable_disablesprite'] ) ) {
//                    if (strpos($site['favicon'], 'http') === 0) {
//                            $imgsrc = $site['favicon'];
//                    } else {
//                            $imgsrc = $imagepath.$site['favicon'];
//                    }
//                    $img = '<img src="' . $imgsrc . '" width="16" height="16" />';
//            } else {
//                    $imgsrc = $imagepath."services-sprite.gif";
//                    $services_sprite_url = $imagepath . "services-sprite.png";
//                    $spriteCoords = $site['spriteCoordinates'];
//                    $img =  '<img src="' . $imgsrc . '" width="16" height="16" style="background: transparent url(' . $services_sprite_url . ') no-repeat; background-position:-' . $spriteCoords[0] . 'px -' . $spriteCoords[1] . 'px" />';
//            }
            
            $out .= '<li id="' . $sitename . '" class="' . $active . '">';
            
            $out .= '<input type="checkbox" id="cb_' . $sitename . '" name="sociable_options[active_sites][' . $sitename . ']" ' . $checked . ' />';
            
            $out .= $image;
            if (!isset($site["counter"])){
            $out .= $sitename;
			}
                
            $out .= '</li>';
            
        }
		
        
        echo $out."</ul>";
        
    }
  
    /*
     * Create The HTML For The Options Page
     */
    function Create_Options_Page(){ 
        global $sociable_options;
		?>
		
		
		
			
			<div class="wrap">
        

            <h2 style="clear:both;"><?php _e( 'Sociable 2 Options' ); ?></h2>

            <form method="post" action="options.php" id="form1" autocomplete="off">
                
                <?php wp_nonce_field('sociable-config'); ?>
                <INPUT type="hidden" class="version-INPUT" id="version" name="sociable_options[version]" value="<?php echo$sociable_options["version"];?>" /> 
                <TABLE class="Title-Box" cellspacing="0" cellpadding="0" id="Preview-Title" style="margin:0 0 0 25px">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" >Preview</TD><TD class="Border-Right"></TD>
				</TR>
				</TABLE>
                <BR/>
			
			<DIV    class="Content-Box" id="Preview-Content">
				<DIV style="margin:0 0 0 25px" align="left" class="Live-Preview" id="Live-Preview" ><?php _e("Live preview of how Sociable will appear on your blog.","sociable")?></DIV>
					
				<BR/>
				
				<DIV style="margin:0 0 0 25px" class="Post-TXT" id="Post-TXT" ><?php  _e("This is your post here...","sociable")?></DIV>
		
				<DIV style="margin:0 0 0 25px" class="Post-subTXT" id="Post-subTXT" >Lorem ipsum dolor sit amet, consectetur adipiscing elit.</DIV>
				
				<BR/>
				<DIV style="margin:0 0 0 25px" id="ShareAndEnjoy"  > <?php do_sociable(); ?></DIV>	
			</DIV>
			<TABLE class="Title-Box" cellspacing="0" cellpadding="0" id="Tagline-Title">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" ><?php  _e("Tagline","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" id="Tagline-Content">
				<DIV  class="Tagline-TXT" id="Tagline-TXT" ><?php  _e('Previously we used "Share and Enjoy", remember the good old days?',"sociable");?></DIV>
					
				<BR/>
				<DIV style="width:100%;height:60px;">
					<INPUT type="text" class="Tagline-INPUT" id="tagline" name="sociable_options[tagline]" value="<?php echo$sociable_options["tagline"];?>" /> 
					
					<DIV class="ToSociable" >
							<INPUT type="checkbox" <?php if (!empty($sociable_options["help_grow"])) echo "checked = 'checked'";?> name="sociable_options[help_grow]" id="LinkToSociable" />
							<?php  _e("Link to Sociable","sociable");?><BR/>
							<SPAN style="font-size:14px;"><?php  _e("(Help us grow, please leave the link so others discover Sociable from your blog)","sociable");?></SPAN>
					</DIV>
				</DIV>
			</DIV>
               
                             
			<TABLE class="Title-Box" cellspacing="0" cellpadding="0" id="Tagline-Title">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" ><?php  _e("Icons to Include","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" id="IconsToInclude-Box" style="">
				<DIV  class="IconsToInclude-TXT" id="IconsToInclude-TXT" >
					<?php  _e("Check the sites you want to appear on your blog.","sociable");?>
				</DIV>
					
				<BR/>
                
                <?php self::do_site_selection_list(); ?>
            </DIV>
		
			<div class="soc_clear"></div>
			
			<TABLE class="Title-Box" cellspacing="0" cellpadding="0" id="IconSize-Title" style="margin-top:20px;">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" ><?php  _e("Icons Size","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" style="margin-left:-3px" id="IconSize-Content">
				<?php
					$checked16 = "";
					$checked32 = "";
					$checked48 = "";
					$checked64 = "";
					if ($sociable_options["icon_size"] == 16) $checked16 = "checked='checked'";
					if ($sociable_options["icon_size"] == 32) $checked32 = "checked='checked'";
					if ($sociable_options["icon_size"] == 48) $checked48 = "checked='checked'";
					if ($sociable_options["icon_size"] == 64) $checked64 = "checked='checked'";
					//echo $checked16;
				?>
				<SPAN class="IconSize-Item">	<INPUT  value="16" type="radio" name="sociable_options[icon_size]" <?php echo$checked16;?> />16x16 Pixels </SPAN>
					
				<SPAN class="IconSize-Item">	<INPUT <?php echo$checked32;?> value="32" type="radio" name="sociable_options[icon_size]" />32x32 Pixels </SPAN>
					
				<SPAN class="IconSize-Item">	<INPUT <?php echo$checked48;?> value="48" type="radio"  name="sociable_options[icon_size]"/>48x48 Pixels </SPAN>
					
				<SPAN class="IconSize-Item">	<INPUT <?php echo$checked64;?> value="64" type="radio" name="sociable_options[icon_size]" />64x64 Pixels </SPAN>
				
				
			</DIV>
			
			<TABLE class="Title-Box" cellspacing="0" cellpadding="0" id="IconSize-Title" style="margin-top:20px;">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" ><?php  _e("Icons Style","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" id="IconSize-Content" style="padding:20px;">
				
				<?php
					$checked1 = "";
					$checked2 = "";
					$checked3 = "";
					$checked4 = "";
					$checked5 = "";
					$checked6 = "";
					if ($sociable_options["icon_option"] == "option1") $checked1 = "checked='checked'";
					if ($sociable_options["icon_option"] == "option2") $checked2 = "checked='checked'";
					if ($sociable_options["icon_option"] == "option3") $checked3 = "checked='checked'";
					if ($sociable_options["icon_option"] == "option4") $checked4 = "checked='checked'";
					if ($sociable_options["icon_option"] == "option5") $checked5 = "checked='checked'";
					if ($sociable_options["icon_option"] == "option6") $checked6 = "checked='checked'";
					
					 $imagepath = isset( $sociable_options['sociable_imagedir'] ) ? $sociable_options['sociable_imagedir'] : '' ;
        
						if ($imagepath == "") {
								$imagepath = trailingslashit( SOCIABLE_HTTP_PATH ) . 'images/';
						} else {		
								$imagepath .= trailingslashit( $imagepath );
						}
							//echo $imagepath;
				?>
				
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked1?> value="option1" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/<?php echo$sociable_options["icon_size"]?>/option1_<?php echo$sociable_options["icon_size"]?>.jpg"  /> </SPAN>
				<BR/><BR/>
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked2?> value="option2" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/<?php echo$sociable_options["icon_size"]?>/option2_<?php echo$sociable_options["icon_size"]?>.jpg"  /> </SPAN>
				<BR/><BR/>
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked3?> value="option3" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/<?php echo$sociable_options["icon_size"]?>/option3_<?php echo$sociable_options["icon_size"]?>.jpg"  />  </SPAN>
				<BR/><BR/>
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked4?> value="option4" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/<?php echo$sociable_options["icon_size"]?>/option4_<?php echo$sociable_options["icon_size"]?>.jpg"  /> </SPAN>
				<BR/><BR/>
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked5?> value="option5" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/<?php echo$sociable_options["icon_size"]?>/option5_<?php echo$sociable_options["icon_size"]?>.jpg"  />  </SPAN>
				<BR/><BR/>				
				<SPAN class="IconStyle-Item">	<INPUT name="sociable_options[icon_option]" <?php echo$checked6?> value="option6" type="radio" /> <IMG  src="<?php echo$imagepath?>icon_styles/16/option_6_16.png"  />  </SPAN>
				<BR/><BR/>				
			</DIV>	
				
			<TABLE class="Title-Box" style="cursor:pointer;"  cellspacing="0" cellpadding="0" onclick="hideOrShow('Locations');" >
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" id="Locations-Title" ><span id="Locations-Tab">+ </span><?php  _e("Locations","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" id="Locations-Content" style="display:none;" >
				<DIV  class="Locations-TXT" id="Locations-TXT" ><?php  _e("Please select the locations that you wish to allow the Sociable plugin to  insert itself.","sociable");?></DIV>
					
				<BR/>
				<DIV align="center" style="width:100%;">
					<TABLE  align="center" class="Locations-List" cellspacing="0" border=0 cellpadding="10">
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Home page","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_front_page"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_front_page]" id="HomePage" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("The front page of the blog (if set to a static page), or the main blog page (if set to your latest posts).","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Blog page","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_home"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_home]" id="BlogPage" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("The home page of the blog if is set to your latest posts, or the posts page if the home page is set to a static page","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Posts","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_single"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_single]" id="Posts" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Single post pages","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Pages","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_page"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_page]" id="Pages" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Individual Wordpress pages","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Category archives","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_category"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_category]" id="CategoryArchives" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Category archive pages","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Date archives","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT  <?php if(!empty($sociable_options["locations"]["is_date"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_date]" id="DateArchives" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Date archive pages","sociable");?> </SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Tag archives","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_tag"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_tag]" id="TagArchives" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Tag archive pages","sociable");?> </SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Author archives","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_author"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_author]" id="AuthorArchives" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Author archive pages","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Search results","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_search"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_search]" id="SearchResults" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Search results pages","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("RSS feeds","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["locations"]["is_rss"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[locations][is_rss]" id="RssFeeds" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("RSS feeds","sociable");?></SPAN>
															
							</TD>
						</TR>
						
						
					</TABLE>	
					<BR/><BR/>
				</DIV>
			</DIV>	
			
			<TABLE class="Title-Box" style="cursor:pointer;" cellspacing="0" cellpadding="0" onclick="hideOrShow('GeneralOptions');">
				<TR>
					<TD class="Border-Left" ></TD><TD  class="BG-Middle" id="GeneralOptions-Title" ><span id="GeneralOptions-Tab"> + </span> <?php  _e("General Options","sociable");?></TD><TD class="Border-Right"></TD>
				</TR>
			</TABLE>
			<BR/>
			
			<DIV class="Content-Box" id="GeneralOptions-Content" style="display:none;" >
				
				<BR/>
				<DIV align="center" style="width:100%;">
					<TABLE  align="center" class="GeneralOptions-List" cellspacing="0" border=0 cellpadding	="10" >
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Automatic mode","sociable")?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["automatic_mode"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[automatic_mode]" id="AutoMode" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to automatically use Sociable on the locations specified?","sociable");?> </SPAN>
															<BR/>
															<SPAN class="sTXT">
																	<?php  _e("If this is unchecked, you will have to use the shortcode[sociable/] or template","sociable");?> tag  ?php if( function_exists( do_sociable() ) ){ do_sociable(); } 
															</SPAN>		
															
							</TD>
						</TR>
						
						
						</TR>
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Use styleSheet","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["use_stylesheet"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[use_stylesheet]" id="UseStyleSheets" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to use the default stylesheet for sociable?","sociable");?></SPAN>
							</TD>
						</TR>
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Use your own icons","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["custom_icons"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[custom_icons]" id="UseStyleSheets" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to use your own icons for sociable?","sociable");?></SPAN>
							</TD>
						</TR>
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Use images","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["use_images"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[use_images]" id="UseImages" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to use the Sociable images? If not, the plugin will insert plain text links.","sociable");?></SPAN>
							</TD>
						</TR>
						
						
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Use alpha mask","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["use_alphamask"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[use_alphamask]" id="AlphaMask" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to use alpha masks on the images (available only on the Original Sociable)?","sociable");?></SPAN>
							</TD>
						</TR>
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Bottom and Top","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["topandbottom"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[topandbottom]" id="TopAndBottom" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("Do you want to use Sociable plugin to show up at the top and bottom?","sociable");?></SPAN>
							</TD>
						</TR>
						<TR valign="top" >
							<TD align="right" class="Title" ><?php  _e("Open in new window","sociable");?></TD>
							<TD align="left" style="width:5px;" ><INPUT <?php if(!empty($sociable_options["new_window"])) echo "checked='checked'"?> type="checkbox" name="sociable_options[new_window]" id="OpenNewWindow" /></TD>
							<TD align="left" class="Content">
															<SPAN class="TXT"><?php  _e("do you want to open the links in a new window?","sociable");?></SPAN>
							</TD>
						</TR>
						
						
						
					</TABLE>	
					
					<BR/><BR/>
				</DIV>
			</DIV>
		
				<?php //<HR style="height:10px;background:#18305d;"/>?>
		<?php settings_fields( 'sociable_options_group' ); ?>
		</FORM>
		<DIV class="Content-Box" >
			<DIV id="ActionsBar">
				<DIV class="SaveChanges" onClick="document.getElementById('form1').submit();" style="cursor:pointer;line-height:15px;"><br/>
					<span style="margin:30px;"><?php  _e("Save Changes","sociable");?></span>
				</DIV>
				<DIV class="ResetSociable" id="sociable_reset" name="sociable_reset" onClick="document.getElementById('sociable_reset_form').submit();" style="cursor:pointer;line-height:15px;font-size:12px;"><br/>
					<span style="margin:40px;margin-left:35px;"><?php  _e("Reset Sociable","sociable");?></span>
				</DIV>
				<DIV class="UninstallSociable" onClick="document.getElementById('sociable_remove_form').submit();"  style="cursor:pointer;line-height:15px;font-size:12px;"><br/>
					<span style="margin:25px;margin-left:20px;"><?php  _e("Completly Uninstall Sociable","sociable");?></span>
				</DIV>
			</DIV>
		</DIV>
		<br>
		<br>
	
			
                

                <?php  //do_settings_sections( 'sociable_options' ); ?>

               

          
            
            <form id="sociable_reset_form" action="" method="POST">
                <?php wp_nonce_field('sociable-reset'); ?>
				<input type="hidden" id="sociable_reset" name="sociable_reset" value="1">
                <?php //submit_button( __( 'Reset Sociable' ) , 'primary', 'sociable_reset', false ); ?>
            </form>
            
            <form id="sociable_remove_form" action="plugins.php" method="POST">
                <?php wp_nonce_field('deactivate-sociable'); ?>
				<input type="hidden" id="sociable_remove" name="sociable_remove" value="1">
                <?php //submit_button( __( 'Completely Uninstall Sociable' ) , 'primary', 'sociable_remove', false ); ?>
            </form>

		</div>

    <?php }
    
    function add_meta_box( $page ){
        add_meta_box( 'sociable_off' , __( 'Disable sociable' ), array( 'sociable_Admin_Options' , 'create_meta_box' ) , $page, 'side', 'default' );
    }
    
    function create_meta_box(){
	global $post;
	$sociableoff = false;
        $checked = '';
	if ( get_post_meta( $post->ID,'_sociableoff',true ) ) {
            $checked = 'checked="checked"';
	}
        wp_nonce_field( 'update_sociable_off' , 'sociable_nonce' );
        echo '<input type="checkbox" id="sociableoff" name="sociableoff" ' . $checked . ' /> <p class="description">' . __('Check This To Disable Sociable 2 On This Post Only.') . '</p>';
	
    }
    
    function save_post( $post_id ){
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return $post_id;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        
        $nonce = ( isset( $_POST['sociable_nonce'] ) ) ? $_POST['sociable_nonce'] : false ;

        if ( ! $nonce ||  ! wp_verify_nonce( $nonce, 'update_sociable_off' ) )
          return $post_id;


        // Check permissions
        if ( 'page' == $_POST['post_type'] ){
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
        } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
        }
        
        //Lets Do This
        if( isset( $_POST['sociableoff'] ) ){
            update_post_meta( $post_id, '_sociableoff' , $_POST['sociableoff'] );
        } else {
            delete_post_meta( $post_id, '_sociableoff' );
        }
        
        return $post_id;
    }
    
    /**
     * This Function Runs Before The Options Are Printed Out.
     */
    function general_options_callback(){
        
        return true;
    }
    
    /**
     * This Function Runs Before The Location Options Are Echoed Out.
     */
    function location_options_callback(){
        echo '<p>' . __( 'Please Select The Locations That You Wish To Allow The Sociable 2 Plugin To Insert The Links.' ) . '</p>';
    }
    
    /**
     * Adds A Function For The add_settings_field(); function
     * 
     * should be passed:
     * $data = array(
     *      'id' => 'field_id_and_name',
     *      'description' => 'field Description Should Go Here, This is Not The Title, Rather The Description'
     * );
     */
    function Checkbox( $data ){
        global $sociable_options;
        
        //Save The Locations As a seperate array option
        if( isset( $data['locations'] ) ){
            $name = 'sociable_options[locations][' . $data['id'] . ']';
            $checked = ( isset( $sociable_options['locations'][$data['id']] ) ) ? 'checked="checked"' : '' ;
        } else {
            $name = 'sociable_options[' . $data['id'] . ']';
            $checked = ( isset( $sociable_options[$data['id']] ) ) ? 'checked="checked"' : '' ;
        }
        
        

	echo '<input ' . $checked . ' id="' . $data['id'] . '" name="' . $name . '" type="checkbox" /> <span class="description">' . $data['description'] . '</span>';

    }
    
    function TextInput( $data ){
        global $sociable_options;
        
        $value = ( isset( $sociable_options[$data['id']] ) ) ? $sociable_options[$data['id']] : '';
        

        echo '<input id="' . $data['id'] . '" name="sociable_options[' . $data['id'] . ']" size="40" type="text" value="' . esc_attr( $value ) . '" /> <br /><span class="description">' . $data['description'] . '</span>';

        
    }
    
    function TextArea( $data ){
        global $sociable_options;
        
        $value = ( isset( $sociable_options[$data['id']] ) ) ? $sociable_options[$data['id']] : '';
        

        echo '<textarea id="' . $data['id'] . '" name="sociable_options[' . $data['id'] . ']" >' . $value . '</textarea> <br /><span class="description">' . $data['description'] . '</span>';

        
    }
    
    function radio( $data ){
        global $sociable_options;
        
        $cur_val = ( isset( $sociable_options[$data['id']] ) ) ? $sociable_options[$data['id']] : 0 ;
        
        echo '<span class="description">' . $data['description'] . '</span><br />';
        foreach( $data['options'] as $value => $option ){
            $selected = ( $value == $cur_val ) ? 'checked="checked"' : '' ;
            echo '<input type="radio" name="sociable_options[' . $data['id'] . ']" value="' . $value . '" ' . $selected . ' /> <span>' . $option . '</span><br />';
        }
    }
    
    
}
?>
<?php function add_ie7() { 		

echo'<!--[if lt IE 7]>
  <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js"
  type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 8]>
  <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" 
  type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->';

} 
//add_action('admin_head', 'add_ie7' ); 
?>