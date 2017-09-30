<?php
$data = array(
				'email'     => 'jiaur11.webqueue@gmail.com',
				'status'    => 'subscribed',
				'firstname' => ' ',
				'lastname'  => ' '
			);
			
			syncMailchimp($data);
			
			function syncMailchimp($data) {
				$apiKey = 'b6da72917e92bca53b301c9b71f8a46f-us15';
				$listId = 'ab412c5e2e';
			
				$memberId = md5(strtolower($data['email']));
				$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
				$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
				echo $url;
				
				echo '<br>'.$memberId;
				
				$json = json_encode(array(
					'email_address' => $data['email'],
					'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
					'merge_fields'  => array(
						'FNAME'     => $data['firstname'],
						'LNAME'     => $data['lastname']
					)
				));
			
				$ch = curl_init($url);
			
				curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
			
				$result = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
			   
			    echo '<br>'.$httpCode;
				return $httpCode;
			}
			
			
?>