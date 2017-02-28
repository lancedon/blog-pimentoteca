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

include 'class_circlecrop.php';

class Game extends CircleCrop{

	private $img_path;
	private $img_path_tmp;
	private $api_key;
	private $api_secret;
	private $type; //0 for circle and 1 for square

	public $fbid;
	public $fbname;
	public $photo_selected;
	public $result;

    function __construct($img_path, $img_path_tmp, $api_key, $api_secret, $type) {

    	$this->img_path 	= $img_path;
		$this->img_path_tmp = $img_path_tmp;
		$this->api_key 		= $api_key;
		$this->api_secret 	= $api_secret;
		$this->type 		= $type;    	
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

		require '../src/facebook.php';

		// Create our Application instance (replace this with your appId and secret).
		$facebook = new Facebook(array(
		  'appId'  => $this->api_key,
		  'secret' => $this->api_secret
		));

		$facebook->setAccessToken($_COOKIE[$this->api_secret]);

		// Get User ID
		$user = $facebook->getUser();

		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.

		if ($user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.	
		    $user_profile = $facebook->api('/me?fields=name,id,picture,gender,birthday,email&');
		    $this->fbid   = $user_profile['id'];
		    $this->fbname = $user_profile['name'];

		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $user = null;
		  }
		}
			
		$this->load_img();

    }

    function load_img(){

		$img_profile = file_get_contents('https://graph.facebook.com/'.$this->fbid.'/picture?type=normal'); //enum{small, normal, album, large, square}
		$file = $this->img_path_tmp . $this->fbid . '.jpg';

		file_put_contents($file, $img_profile);



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

		//grava imagem do profile do usuario na pasta
		$merge = imagecreatefromstring(file_get_contents(  $this->img_path_tmp .$this->fbid.'.jpg' ));

		//carrega imagem escolhida para substituir o blank
		$large = imagecreatefromstring(file_get_contents(  $this->img_path . $this->photo_selected));

		//carrega imagem blank para ser substituida
		$small = imagecreatefromstring(file_get_contents(  $this->img_path . 'blank.jpg'));

		$smallwidth = imagesx($small);
		$smallheight = imagesy($small);

		//verifica se já existe as coordenadas do blank
		if(!file_exists($this->img_path . substr($this->photo_selected,0,strlen($this->photo_selected)-4) . '.json')){

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
			if($this->type == 0){
				$this->load($merge);
				$this->crop();	
				$merge = $this->display();

				$smallwidth = imagesx($merge);
				$smallheight = imagesy($merge);

				$ajuste = 25;

				//$this->display();
				//exit(1);
			}

			// merge da foto do profile com a escolhida no teste
			imagecopymerge($large, $merge, $mostLikely["x"]-$ajuste, $mostLikely["y"]-$ajuste, 0, 0, $smallwidth, $smallheight, 100);

		}else{

			$coordenadas = file_get_contents(  $this->img_path . substr($this->photo_selected,0,strlen($this->photo_selected)-4) . '.json');

			$coordenadas = json_decode($coordenadas);
			
			//circle case
			if($this->type == 0){
				$this->load($merge);
				$this->crop();	
				$merge = $this->display();

				$smallwidth = imagesx($merge);
				$smallheight = imagesy($merge);

				$ajuste = 25;

				//$this->display();
				//exit(1);
			}

	   		// merge da foto do profile com a escolhida no teste
			imagecopymerge($large, $merge, $coordenadas->x - $ajuste, $coordenadas->y - $ajuste, 0, 0, $smallwidth, $smallheight, 100);

		}//if do coordenadas



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