<?php
 	$vendor_user = get_userdata( $userid );
	$vendor_user_email = $vendor_user->user_email;
	$vendor_name = get_user_meta($userid,'vendor_name',true);
	
do_action( 'woocommerce_email_header', 'Rate Butik', $vendor_user_email ); ?>

<p><img src="http://stayfab.dk/wp-content/uploads/2017/08/Stayfab_logo_mail_small.png"></p>

<p><?php echo "Kære ".$bill_fname.' '.$bill_lname; ?></p>

<p><?php echo "Du har for nylig benyttet Stayfab! til at købe en tid hos ".$vendor_name.". Vi vil i den forbindelse gerne høre om du var tilfreds med os og den behandling du fik?"; ?></p>

<p><?php _e( "Vi håber derfor, du vil bruge 1 min på at krydse vores spørgeskema af. Dette vil hjælpe os med at gøre Stayfab og vores samarbejdspartnere endnu bedre.", 'woocommerce' ); ?></p>

<p><?php _e( "Som tak deltager du i lodtrækningen om 2 stk. biografbilletter til Nordisk Film. Vi trækker tre heldige vindere hver måned, og vinderne får direkte besked.", 'woocommerce' ); ?></p>

<p><?php echo $message; ?></p>

<p><?php _e( "Med venlig hilsen,<br>Stayfab!", 'woocommerce' ); ?></p>

<?php



