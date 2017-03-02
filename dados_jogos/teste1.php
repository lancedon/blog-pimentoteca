<?php
//informacoes de debug, nao descomentar
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');

//echo "<pre>".print_r($_COOKIE)."</pre><<<<br>";

#defina aqui o local onde estao todas as imagens do teste
$img_path = dirname(__file__) . '/imgs/teste1/';

#defina aqui o diretorio onde as imagens temporarias (dos usuarios) vao ser salvas
$img_path_tmp = $img_path . 'resultados/';

#link para o qual o teste compartilhado vai apontar 
$origem = "/blog/jogos";

$caption = 'Smart Teste';
$desc = 'Descrição aqui';

include 'class/class_game.php';

$obj = new Game($img_path, $img_path_tmp, $api_key, $api_secret, 1);
$obj->play();

//echo $obj->fbid."<<<<";

if(!$obj->fbid){
	header('Location: http://'.$_SERVER['HTTP_HOST'].$origem);
	exit(0);
}

?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="//connect.facebook.net/en_US/all.js"></script>
<script src="/blog/dados_jogos/js/face.js"></script>

<?php echo $obj->fbname; ?>
<br>

<img src='https://<?php echo $_SERVER['HTTP_HOST'].$obj->result; ?>'><img>

<br>


<div id="shareBtn" >Compartilhar</div>
</br>


<br>
<a href="javascript: location.reload();" >Refazer </a></br>							          
