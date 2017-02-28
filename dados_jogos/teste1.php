<?php
//informacoes de debug, nao descomentar
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', '1');


#defina aqui o local onde estao todas as imagens do teste
$img_path = dirname(__file__) . '/dados_jogos/imgs/teste1/';

#defina aqui o diretorio onde as imagens temporarias (dos usuarios) vao ser salvas
$img_path_tmp = $img_path . 'resultados/';

#link para o qual o teste compartilhado vai apontar 
$origem = "/blog/jogos";

$caption = 'Smart Teste';
$desc = 'Descrição aqui';

include 'class/class_game.php';

$obj = new Game($img_path, $img_path_tmp, $api_key, $api_secret, 1);
$obj->play();

if(!$obj->fbid){
	header('Location: http://'.$_SERVER['HTTP_HOST'].$origem);
	exit(0);
}

?>
<?php echo $obj->fbname; ?>
<br>
<img src='<?php echo $obj->result; ?>'><img>


<div id="shareBtn" >Compartilhar</div>
</br>


<script>
document.getElementById('shareBtn').onclick = function() {
  FB.ui({
    method: 'feed', 
    name: 'Facebook Dialogs', 
    link: 'http://<?php echo $_SERVER['HTTP_HOST'].$origem; ?>', 
    picture: 'http://<?php echo $_SERVER['HTTP_HOST'].substr($obj->result, 0, strpos($obj->result, '?')); ?>', 
    caption: '<?php echo $caption; ?>', 
    description: '<?php echo $desc; ?>' 
  }, function(response){});
}
</script>
<br>
<a href="javascript: location.reload();" >Refazer </a></br>							          
