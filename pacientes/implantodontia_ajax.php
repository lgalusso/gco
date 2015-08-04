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
	if(!verifica_nivel('info_paciente', 'L')) {
		echo $LANG['general']['you_tried_to_access_a_restricted_area'];
		die();
	}
	$paciente = new TImplantodontia();
	if(isset($_POST['Salvar'])) {
		$paciente->LoadImplantodontia($_GET['codigo']);
		foreach($_POST as $chave => $valor) {
            if($chave != 'Salvar') {
                $paciente->SetDados($chave, $valor);
            }
		}
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET['codigo'];
	$paciente->LoadImplantodontia($_GET['codigo']);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET['codigo'], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento', 'enxerto');
	foreach($check as $campo) {
		if($row[$campo] == 'Sim') {
			$chk[$campo]['Sim'] = 'checked';
		} else {
			$chk[$campo]['Não'] = 'checked';
		}
	}
	$acao = '&acao=editar';
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();
	}
?>
<link href="../css/smile.css" rel="stylesheet" type="text/css" />

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="pacientes/img/pacientes.png" alt="<?=$LANG['patients']['manage_patients']?>"> <span class="h3"><?=$LANG['patients']['manage_patients']?> &nbsp;[<?=$strLoCase?>] </span></td>
    </tr>
  </table>
<div class="conteudo" id="table dados">
<br />
<?include('submenu.php')?>
<br>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">

    <tr>
      <td height="26">&nbsp;<?=$LANG['patients']['implantodonty']?> </td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/implantodontia_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="texto">
          <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['has_the_patient_an_implant']?>
            </td>
            <td>
              <input name="tratamento" <?=$chk['tratamento']['Sim']?> type="radio" value="Sim" <?=$disable?> /> <?=$LANG['patients']['yes']?>
              <input name="tratamento" <?=$chk['tratamento']['Não']?> type="radio" value="Não" <?=$disable?> /> <?=$LANG['patients']['no']?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['if_yes_in_which_region']?>
            </td>
            <td>
              <input name="regioes" value="<?=$row['regioes']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['patients_expectations_regarding_the_treatment_of_implant']?>
            </td>
            <td>
              <input name="expectativa" value="<?=$row['expectativa']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['areas_the_implant_must_be_done']?>
            </td>
            <td>
              <input name="areas" value="<?=$row['areas']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['brand_and_size_of_the_implant']?>
            </td>
            <td>
              <input name="marca" value="<?=$row['marca']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['need_to_graft_the_region_to_be_implanted']?>
            </td>
            <td>
              <input name="enxerto" <?=$chk['enxerto']['Sim']?> type="radio" value="Sim" <?=$disable?> /> <?=$LANG['patients']['yes']?>
              <input name="enxerto" <?=$chk['enxerto']['Não']?> type="radio" value="Não" <?=$disable?> /> <?=$LANG['patients']['no']?>
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['kind_of_graft_to_be_performed']?>
            </td>
            <td>
              <input name="tipoenxerto" value="<?=$row['tipoenxerto']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['comments']?>
            </td>
            <td colspan="3">
              <textarea name="observacoes" cols="40" rows="5" class="forms" <?=$disable?>><?=$row['observacoes']?></textarea>
            </td>
          </tr>
        </table>
        </fieldset>
        <br />
        <div align="center"><br />
          <input name="Salvar" type="submit" class="forms" id="Salvar" value="<?=$LANG['patients']['save']?>" <?=$disable?> />
        </div>
      </form>      </td>
    </tr>
  </table>
