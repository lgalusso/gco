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
	if(!checklog()) {
		die($frase_log);
	}
	$paciente = new TOrtodontia();
	if(isset($_POST['Salvar'])) {
		$paciente->LoadOrtodontia($_GET['codigo']);
		foreach($_POST as $chave => $valor) {
            if($chave != 'Salvar') {
                $paciente->SetDados($chave, $valor);
            }
		}
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET['codigo'];
	$paciente->LoadOrtodontia($_GET['codigo']);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET['codigo'], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento');
	foreach($check as $campo) {
		if($row[$campo] == 'Sim') {
			$chk[$campo]['Sim'] = 'checked';
		} else {
			$chk[$campo]['N�o'] = 'checked';
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
      <td height="26">&nbsp;<?=$LANG['patients']['orthodonty']?> </td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/ortodontia_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="texto">
          <tr>
            <td width="50%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['has_the_patient_been_under_orthodontic_treatment_before']?>
            </td>
            <td>
              <input name="tratamento" <?=$chk['tratamento']['Sim']?> type="radio" <?=$disable?> value="Sim" /> <?=$LANG['patients']['yes']?>
              <input name="tratamento" <?=$chk['tratamento']['N�o']?> type="radio" <?=$disable?> value="N�o" /> <?=$LANG['patients']['no']?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['forecast_for_orthodontic_treatment']?>
            </td>
            <td>
              <input name="previsao" value="<?=$row['previsao']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['reasons_for_orthodontic_treatment']?>
            </td>
            <td>
              <input name="razoes" value="<?=$row['razoes']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['patients_degree_of_motivation']?>
            </td>
            <td>
              <input name="motivacao" value="<?=$row['motivacao']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['evaluation_of_profile']?>
            </td>
            <td>
              <input name="perfil" value="<?=$row['perfil']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['facial_symmetry']?>
            </td>
            <td>
              <input name="simetria" value="<?=$row['simetria']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['patients_facial_type']?>
            </td>
            <td>
              <input name="tipologia" value="<?=$row['tipologia']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['patients_dental_class']?>
            </td>
            <td>
              <input name="classe" value="<?=$row['classe']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['cross_bite']?>
            </td>
            <td>
              <input name="mordida" value="<?=$row['mordida']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['spee_curve']?>
            </td>
            <td>
              <input name="spee" value="<?=$row['spee']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['overbite']?>
            </td>
            <td>
              <input name="overbite" value="<?=$row['overbite']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['overjet']?>
            </td>
            <td>
              <input name="overjet" value="<?=$row['overjet']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['midline']?>
            </td>
            <td>
              <input name="media" value="<?=$row['media']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['atm_status']?>
            </td>
            <td>
              <input name="atm" value="<?=$row['atm']?>" size="40" type="text" class="forms" <?=$disable?> />
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td>
              <?=$LANG['patients']['radiographic_analysis']?>
            </td>
            <td colspan="3">
              <textarea name="radio" cols="40" rows="5" class="forms" <?=$disable?>><?=$row['radio']?></textarea>
            </td>
          </tr>
          <tr>
            <td>
              <?=$LANG['patients']['model_analysis']?>
            </td>
            <td colspan="3">
              <textarea name="modelo" cols="40" rows="5" class="forms" <?=$disable?>><?=$row['modelo']?></textarea>
            </td>
          </tr>
          <tr bgcolor="#F8F8F8">
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
          <input name="Salvar" type="submit" class="forms" <?=$disable?> id="Salvar" value="<?=$LANG['patients']['save']?>" />
        </div>
      </form>      </td>
    </tr>
  </table>
