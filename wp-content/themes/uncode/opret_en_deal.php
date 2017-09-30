<style>
.select-editable {
     position:relative;
     background-color:white;
     border:solid grey 1px;
     width:120px;
     height:18px;
 }
 .select-editable select {
     position:absolute;
     top:0px;
     left:0px;
     font-size:14px;
     border:none;
     width:120px;
     margin:0;
 }
 .select-editable input {
     position:absolute;
     top:0px;
     left:0px;
     width:100px;
     padding:1px;
     font-size:12px;
     border:none;
 }
 .select-editable select:focus, .select-editable input:focus {
     outline:none;
 }
</style>
<?php
if(is_user_logged_in())
{
			if(isset($_GET['edit_deal']))
			{
				$success_msg = 'Dine ændringer er blevet gemt.';
			}
	
	if(isset($_GET['deal_product_id']))
	{
			
			$terms = get_the_terms( $_GET['deal_product_id'], 'product_cat' );
			$product_cat_id[] =array();
			foreach ($terms as $term) {
				$product_cat_id[] = $term->name;
			}
			unset($product_cat_id[0]);
			//echo $product_cat_id[1];
			
			$regular_price = get_post_meta( $_GET['deal_product_id'], '_regular_price',true  );
			$sale_price  = get_post_meta( $_GET['deal_product_id'], '_sale_price',true);
			$deal_desc= get_post_field('post_content', $_GET['deal_product_id']);
			$expire_date  = get_post_meta( $_GET['deal_product_id'], '_expire_date',true);
			$expire_time  = get_post_meta( $_GET['deal_product_id'], '_expire_time',true);
			
			$startup_date  = get_post_meta( $_GET['deal_product_id'], '_startup_date',true);
			$startup_time  = get_post_meta( $_GET['deal_product_id'], '_startup_time',true);
			
			
			$dagstilbud_from_date   = get_post_meta( $_GET['deal_product_id'], '_dagstilbud_from_date',true);
			$dagstilbud_to_date     = get_post_meta( $_GET['deal_product_id'], '_dagstilbud_to_date',true);
			$dagstilbud_from_time   = get_post_meta( $_GET['deal_product_id'], '_dagstilbud_from_time',true);
			$dagstilbud_to_time     = get_post_meta( $_GET['deal_product_id'], '_dagstilbud_to_time',true);
			?>
               <div class="row-inner">
    <div class="pos-top pos-center align_left column_parent col-lg-12 boomapps_vccolumn single-internal-gutter">
        <div class="uncol style-light"><div class="uncoltable">
            <div class="uncell  boomapps_vccolumn no-block-padding">
                <div class="uncont">
                    <div class="vc_wp_custommenu wpb_content_element">
                        <div class="widget widget_nav_menu">
                            <div class="menu-butik-dashboard-menu-container">
                                <ul id="menu-butik-dashboard-menu" class="menu-smart sm menu-horizontal" data-smartmenus-id="15006345128595247">
                                    
                                    <li id="menu-item-11599" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11599">
                                    <a href="http://stayfab.dk/opret-en-deal">Opret et tilbud</a></li>
                                   
                                    <li id="menu-item-11600" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11600">
                                    <a href="http://stayfab.dk/mine-produkter">Mine tilbud</a></li>
                                   
                                    <li id="menu-item-11601" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11601">
                                    <a href="http://stayfab.dk/rediger-profil">Rediger min salon/klinik</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<script id="script-129970" type="text/javascript">UNCODE.initRow(document.getElementById("script-129970"));</script>
</div>
<br>
            
            
			<form action="" method="POST">
            
            <select name='product_type' id='product_type' style="margin-top: 0px;margin-bottom: 20px;width: 50%;" onchange="wqFunction()">
				<option value="select"><b>Dags eller månedstilbud</b></option>
				<option value="dagstilbud" <?php if($product_cat_id[1]=='Dagstilbud' || $product_cat_id[2]=='Dagstilbud') echo "selected";?>>Dagstilbud</option>
                <option value="månedstilbud" <?php if($product_cat_id[1]=='Månedstilbud' || $product_cat_id[2]=='Månedstilbud') echo "selected";?>>Månedstilbud</option>
				
			</select>
			<div id='product_type_error' style="color:RED"></div>
            
            
			<!--Overskrift:-->
			<input type="text" name="product_title" id="product_title" readonly value="<?php echo get_the_title( $_GET['deal_product_id'] );?>"><br>
			<div id='product_title_error' style="color:RED">
			</div>
			<input name="edit_deal_id" type="hidden" value="<?php echo $_GET['deal_product_id'];?>">

			<input type="hidden" name="product_category" id="product_category" value="<?php echo $product_cat_id[1];?>">
			<select class="class_deal_category" name="product_category" id="product_category" style="max-width: 50%;min-width: 50%;width: 50%;" disabled>
				<option value="select"><b>Vælg Behandling</b></option>
				<option value="Kvinder klip/frisør" <?php if($product_cat_id[1]=='Kvinder klip/frisør' || $product_cat_id[2]=='Kvinder klip/frisør') echo "selected";?>>Kvinder klip/frisør</option>
				<option value="Mænd klip/frisør" <?php if($product_cat_id[1]=='Mænd klip/frisør' || $product_cat_id[2]=='Mænd klip/frisør') echo "selected";?>>Mænd klip/frisør</option>
				<option value="Kosmetolog behandlinger" <?php if($product_cat_id[1]=='Kosmetolog behandlinger' || $product_cat_id[2]=='Kosmetolog behandlinger') echo "selected";?>>Kosmetolog behandlinger</option>
				<option value="Negle behandlinger" <?php if($product_cat_id[1]=='Negle behandlinger' || $product_cat_id[2]=='Negle behandlinger') echo "selected";?>>Negle behandlinger</option>
				<option value="Elevbehandlinger" <?php if($product_cat_id[1]=='Elevbehandlinger' || $product_cat_id[2]=='Elevbehandlinger') echo "selected";?>>Elevbehandlinger</option>
                <option value="Andet" <?php if($product_cat_id[1]=='Andet' || $product_cat_id[2]=='Andet') echo "selected";?>>Andet</option>
                
			</select>
		   <div id='product_type_error1' style="color:RED">
			</div>
			<br>
		   
			Pris før (inkl. moms):<br>
			<input class="class_deal_pris" type="text" name="normal_price" id="normal_price" value="<?php echo $regular_price;?>" readonly><br>
			Pris efter (inkl. moms): <b>(min. 20% af før prisen)</b><br>
			<input type="text" name="sale_price" id="sale_price" value="<?php echo $sale_price;?>">
            <div id='product_sale_price_err' style="color:RED"></div>
			<br>
			
			Beskrivelse:<br>
			<textarea class="class_deal_description" style="margin-bottom: 20px;" name="product_desc" id="product_desc" rows="10" cols="50" placeholder="Indsæt tilbud beskrivelse her..."><?php echo $deal_desc;?></textarea>
			<?php
			if($_GET['wq_date'])
			{
				$style_date="style='border:1px solid #ff0000'";
				$err_msg = 'Opdater venligst udløbstid af dit tilbud.';
			}
			else
			{
				$style_date='';
				$err_msg = '';
			}
			
			if($product_cat_id[1]=='Månedstilbud' || $product_cat_id[2]=='Månedstilbud')
			{
				$style = 'style="display:block"';
			}
			else
			{
				$style = 'style="display:none"';
			}
			
			
            ?>
            <div style="margin-top:20px" id="product_expire_date" <?php echo $style;?>>
            
			Start dato: 
			<input style="    margin-bottom: 20px;" type="text" name="expire_date" id="expire_date" value="<?php echo $startup_date;?>" <?php echo $style_date;?> />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?>
           
			<?php /*?>Udløbstid:
			<input style="margin-bottom:20px" type="text" name="expire_time" id="expire_time" value="<?php echo $expire_time;?>" <?php echo $style_date;?> />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?><?php */?>
          <?php /*?>  
            Udløbstid:<input name="expire_time" id="expire_time" type="text" pattern="[0-9]{2,2}:[0-9]{2,2}" value="<?php echo $expire_time;?>" />
            <div id="wq_timepick_error" style="color:RED"></div>
            <?php */?>
			</div>
            
            <?php
			if($product_cat_id[1]=='Dagstilbud' || $product_cat_id[2]=='Dagstilbud')
			{
				$style1 = 'style="display:block"';
			}
			else
			{
				$style1 = 'style="display:none"';
			}
            ?>
            <div id="product_expire_date_dagstilbud" <?php echo $style1;?>>
            
			Fra dato: <input style="margin-bottom:20px" type="text" name="from_date" id="from_date" value="<?php echo $dagstilbud_from_date;?>" <?php echo $style_date;?> />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?>
            
            Fra tidspunkt: <input style="margin-bottom:20px" type="text" name="from_time" id="from_time" value="<?php echo $dagstilbud_from_time;?>" <?php echo $style_date;?> pattern="[0-9]{2,2}:[0-9]{2,2}" />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?>
            <div id="wq_to_date" style="display:none">
			Til dato: <input style="margin-bottom:20px" type="text" name="to_date" id="to_date" value="<?php echo $dagstilbud_to_date;?>" <?php echo $style_date;?>  />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?>
           </div>
            Til tidspunkt: <input style="margin-bottom:20px" type="text" name="to_time" id="to_time" value="<?php echo $dagstilbud_to_time;?>" <?php echo $style_date;?> pattern="[0-9]{2,2}:[0-9]{2,2}" />
            <?php if($err_msg){ ?>
            <label style="color:red"><?php echo $err_msg ;?></label><br> <?php } ?>
			</div>
           
			<br> <br>
		   <input type="checkbox" id="chk_opret_deal" name="chk_opret_deal" ><label style="padding-left:10px">Er tilbuddet korrekt udfyldt?</label><br>
		   <input type="submit" value="Gem ændringer" name="edit_submit" id="edit_submit">
		   
		</form>
			
			
		<?php if(!$err_msg) { ?>
		<div style="color:green" id="create_deal_success"><?php echo $success_msg;?></div>
        
        <?php } ?>
		<?php
		if(isset($_GET['submit_deal']))
		{
			echo '<input type="button" id="wq_myproduct" value="Se mine tilbud">';
		}
	
	}
	else
	{
		if(isset($_GET['submit_deal']))
		{
			$success_msg = 'Din deal er blevet oprettet.';
		}
		elseif(isset($_GET['edit_deal']))
		{
			$success_msg = 'Dine ændringer er blevet gemt.';
		}
		
		else
		{
			?>
          <div class="row-inner">
    <div class="pos-top pos-center align_left column_parent col-lg-12 boomapps_vccolumn single-internal-gutter">
        <div class="uncol style-light"><div class="uncoltable">
            <div class="uncell  boomapps_vccolumn no-block-padding">
                <div class="uncont">
                    <div class="vc_wp_custommenu wpb_content_element">
                        <div class="widget widget_nav_menu">
                            <div class="menu-butik-dashboard-menu-container">
                                <ul id="menu-butik-dashboard-menu" class="menu-smart sm menu-horizontal" data-smartmenus-id="15006345128595247">
                                    
                                    <li id="menu-item-11599" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11599">
                                    <a href="http://stayfab.dk/opret-en-deal">Opret et tilbud</a></li>
                                   
                                    <li id="menu-item-11600" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11600">
                                    <a href="http://stayfab.dk/mine-produkter">Mine tilbud</a></li>
                                   
                                    <li id="menu-item-11601" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11601">
                                    <a href="http://stayfab.dk/rediger-profil">Rediger min salon/klinik</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<script id="script-129970" type="text/javascript">UNCODE.initRow(document.getElementById("script-129970"));</script>
</div>
<?php

$args = array(
				  'post_type' => 'product', 
				  'post_status' =>'all',
				  'meta_query' => array(
											array(
												'key'     => 'created_by',
												'value'   => 'admin',
												'compare' => '=',
											),
											
											array(
												'key'     => 'created_for_userid',
												'value'   => get_current_user_id(),
											),
										),
				);	
$product_loop = new WP_Query( $args );
?>
			
			<form action="" method="POST" enctype="multipart/form-data">
            
             <select name='product_type' id='product_type' style="margin-top: 0px;margin-bottom: 20px;max-width: 50%;min-width: 50%;width: 50%;" onchange="wqFunction()">
				<option value="select" >Dags eller månedstilbud</option>
				<option value="dagstilbud">Dagstilbud</option>
                <option value="månedstilbud">Månedstilbud</option>
                
				
			</select>
			<div id='product_type_error' style="color:RED"></div>
            
            
			<!--Overskrift:-->
            
            <select name="product_title" id='product_title' style="margin-top: 0px;margin-bottom: 20px;max-width: 50%;min-width: 50%;width: 50%;" onchange="wqLoad_deal_info()">
                <option value="">Vælg produkt fra dropdown</option>
                <?php
                while ( $product_loop->have_posts() ) : $product_loop->the_post(); 	
                ?>
                <option data-id="<?php echo get_the_ID();?>" value="<?php echo get_the_title();?>"><?php echo get_the_title();?></option>
                <?php
                endwhile;
                ?>
            </select>
            <div id='product_title_loading' style="color:GREEN"></div>
			<br>
            
  		<!--<input type="text" name="product_title" id="product_title"><br>-->
			<div id='product_title_error' style="color:RED">
			</div>
		
		  
            
			<input type="hidden" name="product_category" id="product_category" value="">
			<select class="class_deal_category" name="product_category1" id="product_category1" style="max-width: 50%;min-width: 50%;width: 50%;">
				<option value="select" ><b>Vælg Behandling</b></option>
				<option value="Kvinder klip/frisør">Kvinder klip/frisør</option>
				<option value="Mænd klip/frisør">Mænd klip/frisør</option>
				<option value="Kosmetolog behandlinger">Kosmetolog behandlinger</option>
				<option value="Negle behandlinger">Negle behandlinger</option>
                 <option value="Elevbehandlinger">Elevbehandlinger</option>
				<option value="Andet">Andet</option>
               
			</select>
		   <div id='product_type_error1' style="color:RED">
			</div>
			<br>
		   
			Pris før (inkl. moms):<br>
			<input class="class_deal_pris" type="text" name="normal_price" id="normal_price"><br>
			Pris efter (inkl. moms): <b><label id="wq_deal_type_error_msg"></label></b><br>
			<input type="text" name="sale_price" id="sale_price">
			<div id='product_sale_price_err' style="color:RED"></div>
            <br>
			
			Beskrivelse:<br>
			<textarea class="class_deal_description" style="margin-bottom: 20px;" name="product_desc" id="product_desc" rows="10" cols="50" placeholder="Indsæt tilbud beskrivelse her..."></textarea>
			<div id="product_expire_date" style="display:none">
			Start dato: <input style="margin-bottom:20px" type="text" name="expire_date" id="expire_date" />
			<!--Udløbstid: <input style="margin-bottom:20px" type="text" name="expire_time" id="expire_time" />-->
           <!-- Udløbstid:<input name="expire_time" id="expire_time" type="text" pattern="[0-9]{2,2}:[0-9]{2,2}" />
            <div id="wq_timepick_error" style="color:RED"></div>-->
			</div>
            
            <div id="product_expire_date_dagstilbud" style="display:none">
			Fra dato: <input style="margin-bottom:20px" type="text" name="from_date" id="from_date" />
            Fra tidspunkt: <input style="margin-bottom:20px" type="text" name="from_time" id="from_time" pattern="[0-9]{2,2}:[0-9]{2,2}" />
            <div id="wq_from_time_error" style="color:RED"></div>
			<div id='wq_to_date' style="display:none">Til dato: <input type="text" name="to_date" id="to_date" /></div>
            Til tidspunkt: <input style="margin-bottom:20px" type="text" name="to_time" id="to_time" pattern="[0-9]{2,2}:[0-9]{2,2}" />
            <div id="wq_to_time_error" style="color:RED"></div>
			</div>
			<br> <br>
		   <input type="checkbox" id="chk_opret_deal" name="chk_opret_deal" ><label style="padding-left:10px">Er tilbuddet korrekt udfyldt?</label><br>
			<input type="submit" value="Opret en deal" name="submit" id="opret_deal_submit" disabled>
		   
		</form>
			
			
			<?php
		}
		?>
		<div style="color:green" id="create_deal_success"><?php echo $success_msg;?></div>
		<?php
		if(isset($_GET['submit_deal']))
		{
			echo '<input type="button" id="wq_myproduct" value="Se mine tilbud">';
		}
		if(isset($_GET['edit_deal']))
		{
			echo '<input type="button" id="wq_myproduct" value="Se mine tilbud">';
		}
	}
}
else
{
	echo "Log ind venligst for at oprette et tilbud.";	
}
?>
