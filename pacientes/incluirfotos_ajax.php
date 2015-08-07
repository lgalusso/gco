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
