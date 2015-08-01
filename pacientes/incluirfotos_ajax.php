<?
   /**
    * Gerenciador Cl�nico Odontol�gico
    * Copyright (C) 2006 - 2009
    * Autores: Ivis Silva Andrade - Engenharia e Design(ivis@expandweb.com)
    *          Pedro Henrique Braga Moreira - Engenharia e Programa��o(ikkinet@gmail.com)
    *
    * Este arquivo � parte do programa Gerenciador Cl�nico Odontol�gico
    *
    * Gerenciador Cl�nico Odontol�gico � um software livre; voc� pode
    * redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a
    * P�blica Geral GNU como publicada pela Funda��o do Software Livre
    * (FSF); na vers�o 2 da Licen�a invariavelmente.
    *
    * Este programa � distribu�do na esperan�a que possa ser �til,
    * mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O
    * a qualquer MERCADO ou APLICA��O EM PARTICULAR. Veja a
    * Licen�a P�blica Geral GNU para maiores detalhes.
    *
    * Voc� recebeu uma c�pia da Licen�a P�blica Geral GNU,
    * que est� localizada na ra�z do programa no arquivo COPYING ou COPYING.TXT
    * junto com este programa. Se n�o, visite o endere�o para maiores informa��es:
    * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html (Ingl�s)
    * http://www.magnux.org/doc/GPL-pt_BR.txt (Portugu�s - Brasil)
    *
    * Em caso de d�vidas quanto ao software ou quanto � licen�a, visite o
    * endere�o eletr�nico ou envie-nos um e-mail:
    *
    * http://www.smileodonto.com.br/gco
    * smile@smileodonto.com.br
    *
    * Ou envie sua carta para o endere�o:
    *
    * Smile Odontol�ogia
    * Rua Laudemira Maria de Jesus, 51 - Lourdes
    * Arcos - MG - CEP 35588-000
    *
    *
    */
	include "../lib/config.inc.php";
	include "../lib/func.inc.php";
	include "../lib/classes.inc.php";
	require_once '../lang/'.$idioma.'.php';
	header("Content-type: text/html; charset=ISO-8859-1", true);
	//header("Content-type: image/jpeg", true);
	if(!checklog()) {
		die($frase_log);
	}
	
	$target_dir = "fotos/";
	$target_file = $target_dir .$_POST['nomPaciente']."/".$_FILES["arquivo"]["name"];
	$uploadOk = 1;
	
	if(!empty($_FILES['arquivo']['name'])) {
		
		$check = getimagesize($_FILES['arquivo']['name']);
		if($check !== false) {			
			$uploadOk = 1;
		} else {
			echo "<script language='javascript' type='text/javascript'>alert('El archivo elegido para cargar no es una imagen.')</script>";
			error_log("El archivo elegido para cargar no es una imagen.", 3, "../log/errors.log");
			$uploadOk = 0;
		}
		
		// Check if file already exists
		if (file_exists($target_file)) {			
			$uploadOk = 0;
			error_log("Ya existe el archivo de imagen a subir.", 3, "../log/errors.log");
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "<script language='javascript' type='text/javascript'>alert('Sorry, your file was not uploaded.')</script>";
			error_log("Hubo un error al tratar de subir la imagen.", 3, "../log/errors.log");
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES['arquivo']['name'], $target_file)) {
				//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "<script language='javascript' type='text/javascript'>alert('Hubo un error al tratar de subir la imagen.')</script>";
				error_log("Hubo un error al tratar de subir la imagen.", 3, "../log/errors.log");
			}
		}
		//$codigo = next_autoindex('fotospacientes');
		//$caminho = "fotos/".$_GET[codigo]."/".$codigo.".jpg";
		//$foto = imagecreatefromall($_FILES['arquivo']['tmp_name'], $_FILES['arquivo']['name']);
        //$ratio = imagesx($foto) / imagesy($foto);
        //$siz_x = imagesx($foto);
		//$siz_y = imagesy($foto);
		//$imagem = imagecreatetruecolor($siz_x, $siz_y);
		//$white = imagecolorallocate($imagem, 255, 255, 255);
		/*if(!imagecopyresampled($imagem, $foto, 0, 0, 0, 0, $siz_x, $siz_y, imagesx($foto), imagesy($foto))) {
			echo '<script>alert("Favor enviar apenas fotos com\ntamanho menor que 1MB!")</script>'; die();
		}*/
		//imagejpeg($imagem, 'teste.jpg');
        //$img_data = addslashes(file_get_contents('teste.jpg'));
        //$sql = "INSERT INTO `fotospacientes` (`codigo_paciente`, `foto`, `legenda`) VALUES ('".$_GET['codigo']."', '".$img_data."', '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['legenda']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."')";
        //unlink('teste.jpg');
        //mysql_query($sql) or die(mysql_error());
	}
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/fotos', 'conteudo', 'codigo=<?=$_GET[codigo]?>&acao=editar')";
</script>
