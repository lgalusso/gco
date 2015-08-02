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
	if(!checklog()) {
		die($frase_log);
	}
	$strUpCase = "ALTERAÇÂO";
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$acao = '&acao=editar';
?>
<link href="../css/smile.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {color: #FFFFFF}
-->
</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="pacientes/img/pacientes.png" alt="<?=$LANG['patients']['manage_patients']?>"> <span class="h3"><?=$LANG['patients']['manage_patients']?> [<?=$strLoCase?>] </span></td>
    </tr>
  </table>
<div class="conteudo" id="table dados">
<br />
<?include('submenu.php')?>
<br>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">
    
    <tr>
      <td height="26">&nbsp;<?=$LANG['patients']['photos']?></td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
        <br />
        <fieldset>
        <br />
          <table width="550" border="0" align="center">
            <tr>
<?
	$i = 0;
	$nomPaciente='';

	
	
	$query = mysql_query("SELECT * FROM `pacientes` WHERE `codigo` = '".$_GET[codigo]."'") or die(mysql_error());
	
	
	$row=mysql_fetch_row($query);
	$nomPaciente = $row['1'];
	
	$directory="fotos/".$nomPaciente;
	$dirint = dir($directory);
	
	
	while (($archivo = $dirint->read()) !== false)
	{
		if($i % 2 === 0) {
			echo '</tr><tr>';
		}
		
		
		
?>
            <td width="50%" align="center" valign="top">
              
       <?        
        if (eregi("gif", $archivo) || eregi("jpg", $archivo) || eregi("png", $archivo)){
            echo '<img src="pacientes/'.$directory."/".$archivo.'" height="166" width="222" border="0"><br>';
            
            $fotosquery = mysql_query("SELECT * FROM `fotospacientes` WHERE `codigo_paciente` = '".$_GET[codigo]."'  and `foto` = '".$archivo."' ") or die(mysql_error());
            $rowfotos=mysql_fetch_row($fotosquery);
            echo '<label>'.$rowfotos['3'].'</label><br>';
       ?>            
       <?=((verifica_nivel('pacientes', 'E'))?'<a href="pacientes/descargarfoto_p.php?codigo='.$_GET[codigo].'&paciente='.$nomPaciente.'&nom_foto='.$archivo.'" " target="iframe_upload">'.'Editar Foto'.'</a>':'')?> 
            <br><br>
       <?=((verifica_nivel('pacientes', 'E'))?'<a href="pacientes/excluirfotos_ajax.php?codigo='.$_GET[codigo].'&paciente='.$nomPaciente.'&nom_foto='.$archivo.'" onclick="return confirmLink(this)" target="iframe_upload">'.$LANG['patients']['delete_photo'].'</a>':'')?>
            </td>
     <?   
        }            

		$i++;
	}	
	
	$dirint->close();	
	
?>

           </tr>
        </table> 
        <br />
        </fieldset>
        <br />
        <iframe name="iframe_upload" width="1" height="1" frameborder="0" scrolling="No"></iframe>
          <form id="form2" name="form2" method="POST" action="pacientes/incluirfotos_ajax.php?codigo=<?=$_GET['codigo']?>" enctype="multipart/form-data" target="iframe_upload"> <?/*onsubmit="Ajax('arquivos/daclinica/arquivos', 'conteudo', '');">*/?>
  		  <table width="310" border="0" align="center" cellpadding="0" cellspacing="0">
    		<tr align="center">
              <td width="70%"><?=$LANG['patients']['file']?> <br />
                <input type="file" multiple size="20" name="arquivo[]" id="arquivo" class="forms" <?=$disable?> />
              </td>
            </tr>
    		<tr align="center">
              <td width="70%"><?=$LANG['patients']['legend']?> <br />
                <input type="text" size="33" name="legenda" id="legenda" class="forms" <?=$disable?> />
              </td>
            </tr>
            <tr align="center">
              <td width="30%"> <br />
                <input type="submit" name="Salvar" id="Salvar" value="<?=$LANG['patients']['save']?>" class="forms" <?=$disable?> />
              </td>
            </tr>
          </table>
          <input type="hidden" name="nomPaciente" value="<?=$nomPaciente?>"/>
          </form>
          <br />
      </td>
    </tr>
  </table>
