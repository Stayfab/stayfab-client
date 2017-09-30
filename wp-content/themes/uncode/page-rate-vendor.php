       <title>Rate salonen</title>
        <style type="text/css">.main { 
                width: 900px; 
                margin: 0 auto; 
                height: 700px;
                padding: 20px;
            }

            .header{
                height: 100px;    
            }
            .content{    
                height: 700px;
                padding-top: 15px;
            }
            .footer{
                height: 100px;  
                bottom: 0px;
            }
            .heading{
                color: #FF5B5B;
                margin: 10px 0;
                padding: 10px 0;
            }

            #dv1, #dv0{
                width: 408px;
                background-color:white;
                padding: 15px;
                margin: auto;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

            }
            
            body{
            	background-color: #eaeaea;
            }
           
          
        </style>
        <style>
            /****** Rating Starts *****/
            @import url(http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

            fieldset, label { margin: 0; padding: 0; }
            body{ margin: 20px; }
            h1 { font-size: 1.5em; margin: 10px; }

            .rating { 
                border: none;
                float: left;
            }

            .rating > input { display: none; } 
            .rating > label:before { 
                margin: 5px;
                font-size: 1.25em;
                font-family: FontAwesome;
                display: inline-block;
                content: "\f005";
            }

            .rating > .half:before { 
                content: "\f089";
                position: absolute;
            }

            .rating > label { 
                color: #ddd; 
                float: right; 
            }

            .rating > input:checked ~ label, 
            .rating:not(:checked) > label:hover,  
            .rating:not(:checked) > label:hover ~ label { color: #FFD700;  }

            .rating > input:checked + label:hover, 
            .rating > input:checked ~ label:hover,
            .rating > label:hover ~ input:checked ~ label, 
            .rating > input:checked ~ label:hover ~ label { color: #FFED85;  }     


           
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<?php

if(!$_GET['rate_user_id'])
{
	echo '<h2>You are not authorized to rate this salon</h2>';
}
else
{
	
	global $wpdb;

	$rate_product_id = $_GET['rate_product_id'];
	
	$rate_butik_id = $_GET['rate_user_id'];
	
	$rate_butik_name = get_user_meta($rate_butik_id,'vendor_name',true);
	
	$butik_info = get_userdata($rate_butik_id);
	
	//$rate_butik_name = $butik_info->user_login;
	
	
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$_GET['rate_user_id']." ");
	
	if(count($rate_results)>0)
	{
		foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	}
	else
	{
		$tot_rate_val = 0;
	}
	
	if($tot_rate_val > 0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value = 0;

?>
    <body>
           <div class="content">
                 <div id="dv1">
                 <p style="text-align:center;"><img src="http://stayfab.dk/wp-content/uploads/2017/02/stayfab_logo_transparant_baggrund.png" width="200px"></p>
                   <h1 style="text-align:center;margin-bottom:30px;margin-top: 60px;">Rate salonen</h1>
                   <h3>Anmeldelse af: <?php echo $rate_butik_name;?></h3>
                   <h3>Stjernebedømmelser: <?php echo $butik_rate_value;?></h3>
                   <h3>Anmeld salonen her: </h3>
                   
                    <script>
                        $(document).ready(function () {
                            $("#demo2 .stars").click(function () {
                              //  alert($(this).val());
							    new_rate_val = $(this).val();
							    butik_id = '<?php echo $rate_butik_id;?>';
							 	product_id =  '<?php echo $rate_product_id;?>';
                               
							   
							    $(this).attr("checked");
								
								console.log(new_rate_val+'/'+butik_id+'/'+product_id);
								
								
								var data = {
												'action': 'wq_rate_butik_action',
												'new_rate_val' : new_rate_val,
												'butik_id' : butik_id,
												'product_id' : product_id
											};
				
								jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) 
								{
									if(response)
									{
										alert('Tak for din bedømmelse!');
										window.close();
									}
								});
								
								
								
                            });
                        });
                    </script>
                    <fieldset id='demo2' class="rating">
                        <input class="stars" type="radio" id="star5" name="rating" value="5" />
                        <label class = "full" for="star5" title="Fremragende - 5 stjerner"></label>
                        <input class="stars" type="radio" id="star4half" name="rating" value="4.5" />
                        <label class="half" for="star4half" title="Fremragende - 4.5 stjerner"></label>
                        <input class="stars" type="radio" id="star4" name="rating" value="4" />
                        <label class = "full" for="star4" title="God - 4 stjerner"></label>
                        <input class="stars" type="radio" id="star3half" name="rating" value="3.5" />
                        <label class="half" for="star3half" title="God - 3.5 stjerner"></label>
                        <input class="stars" type="radio" id="star3" name="rating" value="3" />
                        <label class = "full" for="star3" title="Middel - 3 stjerner"></label>
                        <input class="stars" type="radio" id="star2half" name="rating" value="2.5" />
                        <label class="half" for="star2half" title="Middel - 2.5 stjerner"></label>
                        <input class="stars" type="radio" id="star2" name="rating" value="2" />
                        <label class = "full" for="star2" title="Under middel - 2 stjerner"></label>
                        <input class="stars" type="radio" id="star1half" name="rating" value="1.5" />
                        <label class="half" for="star1half" title="Under middel - 1.5 stjerne"></label>
                        <input class="stars" type="radio" id="star1" name="rating" value="1" />
                        <label class = "full" for="star1" title="Dårlig - 1 stjerne"></label>
                        <input class="stars" type="radio" id="starhalf" name="rating" value="0.5" />
                        <label class="half" for="starhalf" title="Dårlig - 0.5 stjerne"></label>
                    </fieldset>
                   
					<div style='clear:both;'></div>
                </div>
               </div>
            </div>
            </div>
    </body>
    
 <?php
} ?>