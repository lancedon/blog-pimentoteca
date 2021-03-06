<?php 
/*
Class game
Developed By Lancedon On 02/2017 

This class receive 4 parameters:

$img_path > The main folder about the game
$img_path_tmp > The temporary folder about the game, all customer pictures will be putted here
$api_key > Facebook API KEY
$api_secret > Facebook API SECRET

This class will do the follows steps:
 - Get the user data and picture using Facebook API
 - Select, by random, a file inside the img_path folder
 - Find the file 'blank.jpg' (the file can be in any color) in that file. (the blank.jpg file needs be putted in the img_path folder)
 - Merge the facebook profile photo with the random image
 - Show the merged image and the Facebook share button

*/

/* facebook sdk v4 
require_once 'facebook-php-sdk/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
*/

//Dados do Facebook
$api_key = '506814939705954';
$api_secret = '57532cae9b1cb2f2938da5791df1c238';



include 'class_circlecrop.php';

class Game extends CircleCrop{

	private $img_path;
	private $img_path_tmp;
	private $api_key;
	private $api_secret;
	private $settings; //all setings about the test type
	private $friends;
	private $facebook;

	public $fbid;
	public $fbname;
	public $photo_selected;
	public $result;


    function __construct($img_path, $img_path_tmp, $api_key, $api_secret, $settings) {

    	$this->img_path 	= $img_path;
		$this->img_path_tmp = $img_path_tmp;
		$this->api_key 		= $api_key;
		$this->api_secret 	= $api_secret;
		$this->settings 	= $settings;    
		$this->friends		= array();	

    }


    public function play(){
 	
/*
		//Facebook sdk v4

    	FacebookSession::setDefaultApplication($this->api_key, $this->api_secret);
 
		// VERIFICA SE ESTA LOGADO 
		$helper = new FacebookJavaScriptLoginHelper();
		try {
		  $session = new FacebookSession($_COOKIE[$this->api_secret]);
		}catch(FacebookRequestException $ex) {
		  // When Facebook returns an error
		}catch(\Exception $ex) {
		  // When validation fails or other local issues
		}

		// caso não esteja logado
		if (!$session) exit('Usuário não logado ou token expirado.');
		 
		// PEGA OS DADOS DO USUÁRIO 
		try {

		  $response = (new FacebookRequest($session, 'GET', '/me?fields=name,id,picture,gender,birthday,email&'));
		  $response->execute(); 

		  $object = $response->getGraphObject();

		  $this->fbid 	= $object->getProperty('id');
		  $this->fbname = $object->getProperty('name');
		  

		} catch (FacebookRequestException $ex) {
		  // echo $ex->getMessage();
		} catch (\Exception $ex) {
		  // echo $ex->getMessage();
		}

*/

		//Facebook sdk v3

		require '/var/www/html/blog/dados_jogos/src/facebook.php';

		// Create our Application instance (replace this with your appId and secret).
		$this->facebook = new Facebook(array(
		  'appId'  => $this->api_key,
		  'secret' => $this->api_secret
		));

		$this->facebook->setAccessToken($_COOKIE[$this->api_secret]);

		// Get User ID
		$user = $this->facebook->getUser();

		//echo ($this->api_secret)."<<";
		//echo ($_COOKIE[$this->api_secret])."<<";
		//echo print_r($user)."<<";

		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.

		if ($user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.	
		    $user_profile = $this->facebook->api('/me?fields=name,id,picture,gender,birthday,email&');


		    //echo print_r($user_profile)."<<";

		    $this->fbid   = $user_profile['id'];
		    $this->fbname = $user_profile['name'];


		    switch ($this->settings["type"]) {

		    	case 1:

		    		echo "aqui";

		    		
					//$friends = $facebook->api('/me/friends');
		    		//echo var_dump($friends);

		    		//$friends = $facebook->api('/me/feed');
/*
		    		$friends = $facebook->api('/me/friends?limit=50');
		    		
		    		foreach($friends['data'] as $likesData){
			               
			                $frid = $likesData['id']; 
			                $frname = $likesData['name']; 
			                $fr = $likesData['is_silhouette']; 
			                $friendArray[$frid] = $frname;
							


			                echo $frname." - ".$frid." - ".$fr."<br>";
			            }

			            echo '<pre>';
		    		echo var_dump($friends);
					echo '</pre>';
*/
					$posts = $this->facebook->api('/me/posts?limit=30');
		    		
		    		//echo '<pre>';
					//echo print_r($friends,true)."----<br>";
		    		//echo '</pre>';

		    		foreach($posts['data'] as $likesData){
			               
			         		//echo '<pre>';
			                //echo print_r($likesData,true);
			                //echo '</pre>';
			                //echo print_r($likesData['id'],true);

			                $post_info = $this->facebook->api('/'.$likesData['id'].'?fields=likes.summary(true),comments.summary(true),shares');

			                foreach($post_info as $post_info_Data){

			                	if(isset($post_info_Data['data']))
			                	foreach($post_info_Data['data'] as $post_info_comments_or_likes){

				                	if($post_info_comments_or_likes['from']){

					                	//echo 'Comment: <pre>';
				                		//echo print_r($post_info_comments_or_likes['from'],true);
				                		//echo '</pre>';
				                		$this->AddFriend($post_info_comments_or_likes['from'],2);

				                	}else{

					                	//echo 'Like:<pre>';
				                		//echo print_r($post_info_comments_or_likes,true);
				                		//echo '</pre>';
				                		$this->AddFriend($post_info_comments_or_likes,1);

				                	}
								}
		                		
			                		
			                }
			               

			            }

			    	break;
			
		    }


		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $user = null;
		  }
		}	
			
		echo '<pre>';
		echo print_r($this->arraySort($this->friends,'total'),true);
		echo '</pre>';
		
		$this->load_img();


    }

    private function arraySort($array, $on, $order=SORT_DESC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        $x=0;
        foreach ($sortable_array as $k => $v) {

            $new_array[$x] = $array[$k];
            $x++;
        }
    }

    return $new_array;
}

    private function AddFriend($friend, $type){

    	$key = array_search($friend['id'], array_column($this->friends, 'id'));

    	if($key != NULL){

    		if($type == 1)
				$this->friends[$key]['sum_like']++;
			else	
				$this->friends[$key]['sum_comment']++;

			$this->friends[$key]['total']++;

    	}else{

    		//$user_profile = $this->facebook->api('/'.$friend['id'].'?fields=id,gender');
    		//https://api.genderize.io/?name=peter

    		//echo "<pre>".print_r($user_profile)."</pre>";

    		$this->friends[] = array(
    								'id' => $friend['id'],
    								'name' => $friend['name'],
    								'sum_like' => (($type == 1) ? 1 : 0),
    								'sum_comment' => (($type == 2) ? 1 : 0),
    								'total' => 	1
    								);

    	}
    	//echo print_r($this->friends).'<br> ------- <br>';
    }

    private function load_img(){

		
		//pegar todas as imagens do diretorio $img_path 
		$dir = opendir($this->img_path);
		if ($dir) {
		    while (($item = readdir($dir)) !== false) {
		        if($item != 'blank.jpg' &&
		           $item != '.'  && 
		           $item != '..' && 
		           $item != 'resultados' && 
				   $item != '.DS_Store' &&
		           substr($item, -4) == '.jpg')
		            	$teste_photos[] = trim($item);
		    }
		    closedir($dir);
		}

		$ajuste = 0;

		//escolher uma (randow)
		$this->photo_selected = $teste_photos[mt_rand(0,count($teste_photos)-1)];

		switch ($this->settings["type"]) {
			case 0:
				
				$img_profile = file_get_contents('https://graph.facebook.com/'.$this->fbid.'/picture?type=normal'); //enum{small, normal, album, large, square}
				$file = $this->img_path_tmp . $this->fbid . '.jpg';

				file_put_contents($file, $img_profile);


				if($this->settings["img"]["resize"]){

					$merge = $this->resize_image($this->img_path_tmp .$this->fbid.'.jpg',
												 $this->settings["img"]["new_size"]["newwidth"],
												 $this->settings["img"]["new_size"]["newheight"]
												 );
				}else{

					//carrega imagem do profile do usuario
					$merge = imagecreatefromstring(file_get_contents(  $this->img_path_tmp .$this->fbid.'.jpg' ));

				}

				//carrega imagem escolhida para substituir o blank
				$large = imagecreatefromstring(file_get_contents(  $this->img_path . $this->photo_selected));

				//carrega imagem blank para ser substituida
				$small = imagecreatefromstring(file_get_contents(  $this->img_path . 'blank.jpg'));
			
				$large = $this->insert_profile_img($small, $large, $merge);

				$large = $this->write_name($large);

				//echo "<pre>".var_dump($this->settings)."</pre><br>";

				break;

			case 1:
				
				//order friends
				$this->friends = $this->arraySort($this->friends,'total');

				//carrega imagem escolhida para substituir o blank
				$large = imagecreatefromstring(file_get_contents(  $this->img_path . $this->photo_selected));


				$fbid = $this->fbid;
				$fbname = $this->fbname;

				foreach ($this->settings["imgs"] as $k => $v){	

					//imgs = 0 is the person who doing the test
					if($k==0){	
						
						$this->fbid = $fbid;
						$this->fbname = $fbname;

					}else{

						$tot_friends = count($this->friends);
						//$x = mt_rand(1, ($tot_friends > 5 ? 5 : $tot_friends));
						//if($tot_friends >= $k+1)
						//	break;

						$this->fbid = $this->friends[$k-1]['id'];
						$this->fbname = $this->friends[$k-1]['name'];

					}

					$this->settings["img"] = $v["img"];
					$this->settings["name"] = $v["name"];

					$img_profile = file_get_contents('https://graph.facebook.com/'.$this->fbid.'/picture?type=normal'); //enum{small, normal, album, large, square}
					$file = $this->img_path_tmp . $this->fbid . '.jpg';

					file_put_contents($file, $img_profile);


					if($this->settings["img"]["resize"]){

						$merge = $this->resize_image($this->img_path_tmp .$this->fbid.'.jpg',
													 $this->settings["img"]["new_size"]["newwidth"],
													 $this->settings["img"]["new_size"]["newheight"]
													 );
					}else{

						//carrega imagem do profile do usuario
						$merge = imagecreatefromstring(file_get_contents(  $this->img_path_tmp .$this->fbid.'.jpg' ));

					}

					//carrega imagem blank para ser substituida
					$small = imagecreatefromstring(file_get_contents(  $this->img_path . 'blank.jpg'));
				
					$large = $this->insert_profile_img($small, $large, $merge);

					$large = $this->write_name($large);

					$this->settings["img"] = NULL;
					$this->settings["name"] = NULL;

					//echo "<pre>".var_dump($this->settings)."</pre><br>";

				}	


				$this->fbid = $fbid;
				$this->fbname = $fbname;

				break;

		}

		//write img on file (real path)
		$this->result = $this->img_path_tmp.$this->fbid.'.jpg';
		imagejpeg($large, $this->result);

		
		//get url for web relative path
		//$this->result = getcwd() . $this->img_path_tmp.$this->fbid.'.jpg';
		$this->result = '/'.str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->result . '?' . filemtime($this->img_path_tmp.$this->fbid.'.jpg'));

		//echo $this->img_path_tmp.$this->fbid.'.jpg <br>'.$_SERVER['DOCUMENT_ROOT'];
		//echo '<br>'.$this->result;

		//free memory
		imagedestroy($small);
		imagedestroy($large);

    }

    private function write_name($large){


				/* @Parametros
				 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
				 * 255 - Cor vermelha ( RGB )
				 * 255 - Cor verde ( RGB )
				 * 255 - Cor azul ( RGB )
				 * -- No caso acima é branco
				 */
				$color = imagecolorallocate( $large,
										   $this->settings["name"]["color"]["red"],
										   $this->settings["name"]["color"]["green"],
										   $this->settings["name"]["color"]["blue"]
										   );

				/* @Parametros
				 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
				 * 5 - tamanho da fonte. Valores de 1 a 5
				 * 15 - Posição X do texto na imagem
				 * 515 - Posição Y do texto na imagem
				 * $nome - Texto que será escrito
				 * $cor - Cor criada pelo imagecolorallocate
				 */
				imagettftext( $large,
							 $this->settings["name"]["size"], 
							 0,
							 $this->settings["name"]["x"], 
							 $this->settings["name"]["y"], 
							 $color,
							 $this->settings["name"]["font"],
							 $this->fbname 
							  );

				return $large;

    }

    private function insert_profile_img($small, $large, $merge){

		$smallwidth = imagesx($small);
		$smallheight = imagesy($small);

		//verifica se já existe as coordenadas do blank
		if(!file_exists($this->img_path . substr($this->photo_selected,0,strlen($this->photo_selected)-4) . '.json') && $this->settings["img"]["find_pos"]){

			$largewidth = imagesx($large);
			$largeheight = imagesy($large);

			$foundX = -1;
			$foundY = -1;

			$keepThreshold = 20;

			$potentialPositions = array();

			$stepSize = 10;

			for($x = 0; $x <= $largewidth - $smallwidth; $x = $x + $stepSize)
			{
			    for($y = 0; $y <= $largeheight - $smallheight; $y = $y + $stepSize)
			    {
			        // Scan the whole picture
			        $error = $this->GetImageErrorAt($large, $small, $x, $y, 2);
			        if($error["complete"] == true && $error["avg"] < $keepThreshold)
			        {
			            array_push($potentialPositions, array("x" => $x, "y" => $y, "error" => $error));
			        }
			    }
			}

			if(count($potentialPositions) > 0)
			{
			    usort($potentialPositions, array ('game', 'SortOnAvgError'));
			    $mostLikely = $potentialPositions[0];
			    //echo "Most probably around " . $mostLikely["x"] . "," . $mostLikely["y"] . "\n";

			    $startX = $mostLikely["x"] - $stepSize + 1; // - $stepSize was already explored
			    $startY = $mostLikely["y"] - $stepSize + 1; // - $stepSize was already explored

			    $endX = $mostLikely["x"] + $stepSize - 1;
			    $endY = $mostLikely["y"] + $stepSize - 1;

			    $refinedPositions = array();

			    for($x = $startX; $x <= $endX; ++$x)
			    {
			        for($y = $startY; $y <= $endY; ++$y)
			        {
			            // Scan the whole picture
			            $error = $this->GetImageErrorAt($large, $small, $x, $y, 1); // now check every pixel!
			            if($error["avg"] < $keepThreshold) // make the threshold smaller
			            {
			                array_push($refinedPositions, array("x" => $x, "y" => $y, "error" => $error));
			            }
			        }
			    }

			    if(count($refinedPositions))
			    {
			        usort($refinedPositions, array ('game', 'SortOnAvgError'));
			        $mostLikely = $refinedPositions[0];
			    }
			}

			//caso nao encontre o blank, coloca a imagem no 0,0
			if(!$mostLikely){

			    $mostLikely["x"]=1;
			    $mostLikely["y"]=1;

			}

			//grava as coordenadas em arquivo JSON
			file_put_contents( $this->img_path . substr($this->photo_selected,0,strlen($this->photo_selected)-4) . '.json', json_encode(array('x' => $mostLikely["x"], 'y' => $mostLikely["y"])));

	

			//circle case
			if($this->settings["img"]["circle"] == 1){
				$this->load($merge);
				$this->crop();	
				$merge = $this->display();

				$ajuste = 25;

				//$this->display();
				//exit(1);
			}


			$smallwidth = imagesx($merge);
			$smallheight = imagesy($merge);


			// merge da foto do profile com a escolhida no teste
			imagecopymerge($large, $merge, $mostLikely["x"]-$ajuste, $mostLikely["y"]-$ajuste, 0, 0, $smallwidth, $smallheight, 100);

		}else{

			if($this->settings["img"]["find_pos"] == 0){

				$coordenadas =  json_decode(json_encode($this->settings["img"]));

			}else{

				$coordenadas = file_get_contents(  $this->img_path . substr($this->photo_selected,0,strlen($this->photo_selected)-4) . '.json');

				$coordenadas = json_decode($coordenadas);
				
			}

			
			//circle case
			if($this->settings["img"]["circle"] == 1){
				$this->load($merge);
				$this->crop();	
				$merge = $this->display();

				$ajuste = 25;

				//$this->display();
				//exit(1);
			}

			$smallwidth = imagesx($merge);
			$smallheight = imagesy($merge);


	   		// merge da foto do profile com a escolhida no teste
			imagecopymerge($large, $merge, $coordenadas->x - $ajuste, $coordenadas->y - $ajuste, 0, 0, $smallwidth, $smallheight, 100);

		}//if do coordenadas

	return $large;

    }


    private function resize_image($file, $w, $h, $crop=FALSE) {
	    list($width, $height) = getimagesize($file);
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*abs($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*abs($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	        if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }
	    $src = imagecreatefromjpeg($file);
	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

	private function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}

	private function GetImageErrorAt($haystack, $needle, $startX, $startY, $increment)
	{
	    $needleWidth = imagesx($needle);
	    $needleHeight = imagesy($needle);

	    $error = array("red" => 0, "green" => 0, "blue" => 0, "avg" => 0, "complete" => true);

	    for($x = 0; $x < $needleWidth; $x = $x + $increment)
	    {
	        for($y = 0; $y < $needleHeight; $y = $y + $increment)
	        {
	            $hrgb = imagecolorat($haystack, $x + $startX, $y + $startY);
	            $nrgb = imagecolorat($needle, $x, $y);

	            $nr = $nrgb & 0xFF;
	            $hr = $hrgb & 0xFF;

	            $ng = ($nrgb >> 8) & 0xFF;
	            $hg = ($hrgb >> 8) & 0xFF;

	            $nb = ($nrgb >> 16) & 0xFF;
	            $hb = ($hrgb >> 16) & 0xFF;

	            $error["red"] += abs($hr - $nr);
	            $error["green"] += abs($hg - $ng);
	            $error["blue"] += abs($hb - $nb);
	        }
	    }

	    $error["avg"] = ($error["red"] + $error["green"] + $error["blue"]) / ($needleWidth * $needleHeight);

	    return $error;
	}

	private function SortOnAvgError($a, $b)
	{
	    if($a["error"]["avg"] == $b["error"]["avg"])
	    {
	        return 0;
	    }
	    return ($a["error"]["avg"] < $b["error"]["avg"]) ? -1 : 1;
	}

}
