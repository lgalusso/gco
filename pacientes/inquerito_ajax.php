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
	
	if(!verifica_nivel('info_paciente', 'L')) {
		echo $LANG['general']['you_tried_to_access_a_restricted_area'];
		die();
	}
	
	$paciente = new TInquerito();
	if(isset($_POST[Salvar])) {	
		$paciente->LoadInquerito($_GET[codigo]);
		//$strScrp = "Ajax('pacientes/gerenciar', 'conteudo', '')";
		$paciente->SetDados('tratamento', $_POST[tratamento]);
		$paciente->SetDados('motivotrat', $_POST[motivotrat]);
		$paciente->SetDados('hospitalizado', $_POST[hospitalizado]);
		$paciente->SetDados('motivohosp', $_POST[motivohosp]);
		$paciente->SetDados('cardiovasculares', $_POST[cardiovasculares]);
		$paciente->SetDados('sanguineo', $_POST[sanguineo]);
		$paciente->SetDados('reumatico', $_POST[reumatico]);
		$paciente->SetDados('respiratorio', $_POST[respiratorio]);
		$paciente->SetDados('qualresp', $_POST[qualresp]);
		$paciente->SetDados('gastro', $_POST[gastro]);
		$paciente->SetDados('qualgastro', $_POST[qualgastro]);
		$paciente->SetDados('renal', $_POST[renal]);
		$paciente->SetDados('diabetico', $_POST[diabetico]);
		$paciente->SetDados('contagiosa', $_POST[contagiosa]);
		$paciente->SetDados('qualcont', $_POST[qualcont]);
		$paciente->SetDados('anestesia', $_POST[anestesia]);
		$paciente->SetDados('complicacoesanest', $_POST[complicacoesanest]);
		$paciente->SetDados('alergico', $_POST[alergico]);
		$paciente->SetDados('qualalergico', $_POST[qualalergico]);
		$paciente->SetDados('observacoes', $_POST[observacoes]);
		$paciente->Salvar();
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$paciente->LoadInquerito($_GET[codigo]);
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	$row = $paciente->RetornaTodosDados();
	$check = array('tratamento', 'hospitalizado', 'cardiovasculares', 'sanguineo', 'reumatico', 'respiratorio', 'gastro', 'renal', 'diabetico', 'contagiosa', 'anestesia', 'alergico');
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
      <td height="26">&nbsp;<?=$LANG['patients']['health_investigation']?> </td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/inquerito_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td width="282">&nbsp;</td>
            <td width="112">&nbsp;</td>
            <td width="86"><div align="right"></div></td>
            <td width="126">&nbsp;</td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['is_the_patient_under_medical_and_or_surgical_treatment']?></td>
            <td><input name="tratamento" <?=$chk[tratamento]['Sim']?> type="radio" <?=$disable?> value="Sim" />
              <?=$LANG['patients']['yes']?>
                <input name="tratamento" <?=$chk[tratamento]['N�o']?> type="radio" <?=$disable?> value="N�o" />
            <?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['reason']?>&nbsp; </div></td>
            <td><input name="motivotrat" value="<?=$row[motivotrat]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr>
            <td height="21"><?=$LANG['patients']['has_the_patient_been_hospitalized']?> </td>
            <td><input name="hospitalizado" <?=$chk[hospitalizado]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="hospitalizado" <?=$chk[hospitalizado]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['reason']?>:&nbsp; </div></td>
            <td><input name="motivohosp" value="<?=$row[motivohosp]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['does_the_patient_suffer_from_cardiovascular_disorders']?></td>
            <td><input name="cardiovasculares" <?=$chk[cardiovasculares]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="cardiovasculares" <?=$chk[cardiovasculares]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['does_the_patient_suffer_some_blood_disorder']?> </td>
            <td><input name="sanguineo" <?=$chk[sanguineo]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="sanguineo" <?=$chk[sanguineo]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['does_the_patient_present_a_history_of_rheumatic_fever']?> </td>
            <td><input name="reumatico" <?=$chk[reumatico]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="reumatico" <?=$chk[reumatico]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['does_the_patient_suffer_some_respiratory_disorder']?> </td>
            <td><input name="respiratorio" <?=$chk[respiratorio]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="respiratorio" <?=$chk[respiratorio]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['which']?>?&nbsp;</div></td>
            <td><input name="qualresp" value="<?=$row[qualresp]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['does_the_patient_suffer_some_grastrointestinal_disorder']?> </td>
            <td><input name="gastro" <?=$chk[gastro]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="gastro" <?=$chk[gastro]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['which']?>?&nbsp;</div></td>
            <td><input name="qualgastro" value="<?=$row[qualgastro]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['does_the_patient_suffer_a_kidney_disorder']?></td>
            <td><input name="renal" <?=$chk[renal]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="renal" <?=$chk[renal]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['is_the_patient_diabetic']?></td>
            <td><input name="diabetico" <?=$chk[diabetico]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="diabetico" <?=$chk[diabetico]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['has_or_had_the_patient_infectious_diseases']?> </td>
            <td><input name="contagiosa" <?=$chk[contagiosa]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="contagiosa" <?=$chk[contagiosa]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['which']?>?&nbsp;</div></td>
            <td><input name="qualcont" value="<?=$row[qualcont]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr bgcolor="#F8F8F8">
            <td><?=$LANG['patients']['did_the_patient_take_dental_anesthesia']?> </td>
            <td><input name="anestesia" <?=$chk[anestesia]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="anestesia" <?=$chk[anestesia]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['complications']?>&nbsp;</div></td>
            <td><input name="complicacoesanest" value="<?=$row[complicacoesanest]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['is_the_patient_allergic_to_any_medicine']?> </td>
            <td><input name="alergico" <?=$chk[alergico]['Sim']?> type="radio" <?=$disable?> value="Sim" />
<?=$LANG['patients']['yes']?>
  <input name="alergico" <?=$chk[alergico]['N�o']?> type="radio" <?=$disable?> value="N�o" />
<?=$LANG['patients']['no']?></td>
            <td><div align="right"><?=$LANG['patients']['which']?>?&nbsp;</div></td>
            <td><input name="qualalergico" value="<?=$row[qualalergico]?>" type="text" class="forms" <?=$disable?> /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['comments']?></td>
            <td colspan="3"><textarea name="observacoes" cols="40" rows="5" class="forms" <?=$disable?>><?=$row[observacoes]?></textarea></td>
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
