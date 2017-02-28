<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');

require_once 'facebook-php-sdk/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
 
FacebookSession::setDefaultApplication('701376056703205', 'fc34b10471c3e19d03e1c7c80bbbe3fd');
 
/* VERIFICA SE ESTA LOGADO */
$helper = new FacebookJavaScriptLoginHelper();
try {
  $session = new FacebookSession($_COOKIE['fc34b10471c3e19d03e1c7c80bbbe3fd']);
}catch(FacebookRequestException $ex) {
  // When Facebook returns an error
}catch(\Exception $ex) {
  // When validation fails or other local issues
}

// caso não esteja logado
if (!$session) exit('Usuário não logado ou token expirado.');
 
/* PEGA OS DADOS DO USUÁRIO */
try {
  

$response = (new FacebookRequest($session, 'GET', '/me?fields=name,id,picture,gender,birthday,email&'))->execute(); //?fields=name,id,picture,gender,birthday,email
 
  $object = $response->getGraphObject();
//echo print_r($object);

  $fbid = $object->getProperty('id');
  $fbname = $object->getProperty('name');
  $fbgender = $object->getProperty('gender');
  $fbbirtday = $object->getProperty('birthday');
  $fbemail = $object->getProperty('email');
 
} catch (FacebookRequestException $ex) {
  // echo $ex->getMessage();
} catch (\Exception $ex) {
  // echo $ex->getMessage();
}
 

$img = file_get_contents('https://graph.facebook.com/'.$fbid.'/picture?type=normal'); //enum{small, normal, album, large, square}
$file = dirname(__file__).'/avatar/'.$fbid.'.jpg';
file_put_contents($file, $img);

/*
echo 'Name='.$fbname;
echo '<br>Id='.$fbid;
echo '<br>Gender='.$fbgender;
echo '<br>Aniversario='.($fbbirtday);
echo '<br>Email='.$fbemail;
echo "<br>Photo=<img src='/face/avatar/".$fbid.".jpg'></img>";
*/

//gravar no DB


//localizar blank


$time_start = microtime_float();

//$small = imagecreatefromstring($img);
$merge = imagecreatefromstring(file_get_contents( dirname(__file__).'/avatar/'.$fbid.'.jpg' ));

$small = imagecreatefromstring(file_get_contents( dirname(__file__) . '/avatar/blank.jpg'));
$large = imagecreatefromstring(file_get_contents( dirname(__file__) . '/avatar/teste.jpg'));
//$small = imageCreateFromJpeg("/face/avatar/blank.jpg");
//$large = imageCreateFromJpeg("/face/avatar/teste.jpg");

$smallwidth = imagesx($small);
$smallheight = imagesy($small);

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
        $error = GetImageErrorAt($large, $small, $x, $y, 2);
        if($error["complete"] == true && $error["avg"] < $keepThreshold)
        {
            array_push($potentialPositions, array("x" => $x, "y" => $y, "error" => $error));
        }
    }
}

//echo "<br>Found " . count($potentialPositions) . " potential positions\n<br>";
  

$time_end = microtime_float();
//  echo "in " . ($time_end - $time_start) . " seconds\n";



if(count($potentialPositions) > 0)
{
    usort($potentialPositions, "SortOnAvgError");
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
            $error = GetImageErrorAt($large, $small, $x, $y, 1); // now check every pixel!
            if($error["avg"] < $keepThreshold) // make the threshold smaller
            {
                array_push($refinedPositions, array("x" => $x, "y" => $y, "error" => $error));
            }
        }
    }

    //echo "Found " . count($refinedPositions) . " refined positions\n";
    if(count($refinedPositions))
    {
        usort($refinedPositions, "SortOnAvgError");
        $mostLikely = $refinedPositions[0];
        //echo "Most likely at " . $mostLikely["x"] . "," . $mostLikely["y"] . "\n";
    }
}



//merge

// Copy and merge
imagecopymerge($large, $merge, $mostLikely["x"], $mostLikely["y"], 0, 0, $smallwidth, $smallheight, 100);


// Cor de saída
$cor = imagecolorallocate( $large, 255, 255, 255 );
/* @Parametros
 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
 * 255 - Cor vermelha ( RGB )
 * 255 - Cor verde ( RGB )
 * 255 - Cor azul ( RGB )
 * -- No caso acima é branco
 */
 
// Texto que será escrito na imagem
$nome = urldecode( 'teste' );
/* @Parametros
 * $_GET['nome'] - Texto que será escrito
 */
 
// Escrever nome
imagestring( $large, 5, 15, 515, $nome, $cor );
/* @Parametros
 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
 * 5 - tamanho da fonte. Valores de 1 a 5
 * 15 - Posição X do texto na imagem
 * 515 - Posição Y do texto na imagem
 * $nome - Texto que será escrito
 * $cor - Cor criada pelo imagecolorallocate
 */


// Output and free from memory
header('Content-Type: image/jpeg');
imagejpeg($large);




imagedestroy($small);
imagedestroy($large);



function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function GetImageErrorAt($haystack, $needle, $startX, $startY, $increment)
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


function SortOnAvgError($a, $b)
{
    if($a["error"]["avg"] == $b["error"]["avg"])
    {
        return 0;
    }
    return ($a["error"]["avg"] < $b["error"]["avg"]) ? -1 : 1;
}

?>