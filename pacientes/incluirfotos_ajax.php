<?
   /**
    * Gerenciador Clínico Odontológico
    * Copyright (C) 2006 - 2009
    * Autores: Ivis Silva Andrade - Engenharia e Design(ivis@expandweb.com)
    *          Pedro Henrique Braga Moreira - Engenharia e Programação(ikkinet@gmail.com)
    *
    * Este arquivo é parte do programa Gerenciador Clínico Odontológico
    *
    * Gerenciador Clínico Odontológico é um software livre; você pode
    * redistribuí-lo e/ou modificá-lo dentro dos termos da Licença
    * Pública Geral GNU como publicada pela Fundação do Software Livre
    * (FSF); na versão 2 da Licença invariavelmente.
    *
    * Este programa é distribuído na esperança que possa ser útil,
    * mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÂO
    * a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
    * Licença Pública Geral GNU para maiores detalhes.
    *
    * Você recebeu uma cópia da Licença Pública Geral GNU,
    * que está localizada na raíz do programa no arquivo COPYING ou COPYING.TXT
    * junto com este programa. Se não, visite o endereço para maiores informações:
    * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html (Inglês)
    * http://www.magnux.org/doc/GPL-pt_BR.txt (Português - Brasil)
    *
    * Em caso de dúvidas quanto ao software ou quanto à licença, visite o
    * endereço eletrônico ou envie-nos um e-mail:
    *
    * http://www.smileodonto.com.br/gco
    * smile@smileodonto.com.br
    *
    * Ou envie sua carta para o endereço:
    *
    * Smile Odontolóogia
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
	
	$target_dir = "fotos/".$_POST['nomPaciente']."/";
	
	// Iteramos sobre el array de imagenes a subir
	$total = sizeof($_FILES["arquivo"]["name"]); 
	$i = 0;
	
	while($i < $total){
		
		$target_file = $target_dir.$_FILES["arquivo"]["name"][$i];
		$uploadOk = 1;
		
		if(!empty($_FILES['arquivo']['name'][$i])) {
		
			$check = getimagesize($_FILES['arquivo']['tmp_name'][$i]);
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
				echo "<script language='javascript' type='text/javascript'>alert('Ya existe el archivo de imagen a subir.')</script>";
			}
		
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "<script language='javascript' type='text/javascript'>alert('Hubo un error al tratar de subir la imagen.')</script>";
				
				// if everything is ok, try to upload file
			} else {
				//Verificamos que exista la carpeta destino
				if ( ! is_dir($target_dir)) {
					mkdir($target_dir);
				}
				if (move_uploaded_file($_FILES['arquivo']['tmp_name'][$i], $target_file)) {
					$sql = "INSERT INTO `fotospacientes` (`codigo_paciente`, `foto`, `legenda`) VALUES ('".$_GET['codigo']."', '".$_FILES["arquivo"]["name"][$i]."', '".utf8_decode ( htmlspecialchars( utf8_encode($_POST['legenda']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') )."')";
					mysql_query($sql) or die(mysql_error());
					
				} else {
					echo "<script language='javascript' type='text/javascript'>alert('Hubo un error al tratar de subir la imagen.')</script>";					
				}
			}	
		
		}
	$i++;
	
	}
?>
<script language="javascript" type="text/javascript">
window.parent.location.href="javascript:Ajax('pacientes/fotos', 'conteudo', 'album=<?=$_POST['nomPaciente']?>&codigo=<?=$_GET[codigo]?>&acao=editar')";
</script>
