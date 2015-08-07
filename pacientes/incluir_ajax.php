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
	$paciente = new TPacientes();
	if(isset($_POST['Salvar'])) {
        $_POST['tratamento'] = @implode(',', $_POST['tratamento']);
		$obrigatorios[1] = 'codigo';
		$obrigatorios[] = 'nom';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
				$r[$i] = '<font color="#FF0000">';
			    $j++;
			}
		}
		if(!is_valid_codigo($_POST[codigo]) && $_GET[acao] != "editar") {
			$j++;
			$r[1] = '<font color="#FF0000">';
        }
		if($j === 0) {
			if($_GET[acao] == "editar") {
				$paciente->LoadPaciente($_POST[codigo]);
				$strScrp = "Ajax('pacientes/gerenciar', 'conteudo', '')";
			}
			$paciente->SetDados('codigo', $_POST[codigo]);
			$paciente->SetDados('nome', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nom']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('cpf', $_POST[cpf]);
			$paciente->SetDados('rg', $_POST[rg]);
			$paciente->SetDados('estadocivil', utf8_decode ( htmlspecialchars( utf8_encode($_POST['estadocivil']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('sexo', $_POST[sexo]);
			$paciente->SetDados('etnia', $_POST[etnia]);
			$paciente->SetDados('profissao', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissao']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('naturalidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['naturalidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('nacionalidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nacionalidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('nascimento', converte_data($_POST[nascimento], 1));
			$paciente->SetDados('endereco', utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('estado', $_POST[estado]);
			$paciente->SetDados('pais', utf8_decode ( htmlspecialchars( utf8_encode($_POST['pais']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('falecido', $_POST[falecido]);
			$paciente->SetDados('cep', $_POST[cep]);
			$paciente->SetDados('celular', $_POST[celular]);
			$paciente->SetDados('telefone1', $_POST[telefone1]);
			$paciente->SetDados('telefone2', $_POST[telefone2]);
			$paciente->SetDados('hobby', utf8_decode ( htmlspecialchars( utf8_encode($_POST['hobby']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('indicadopor', utf8_decode ( htmlspecialchars( utf8_encode($_POST['indicadopor']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('email', $_POST[email]);
			$paciente->SetDados('obs_etiqueta', utf8_decode ( htmlspecialchars( utf8_encode($_POST['obs_etiqueta']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('tratamento', $_POST[tratamento]);
			$paciente->SetDados('codigo_dentistaprocurado', $_POST[codigo_dentistaprocurado]);
			$paciente->SetDados('codigo_dentistaatendido', $_POST[codigo_dentistaatendido]);
			$paciente->SetDados('codigo_dentistaencaminhado', $_POST[codigo_dentistaencaminhado]);
			$paciente->SetDados('nomemae', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomemae']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('nascimentomae', converte_data($_POST[nascimentomae], 1));
			$paciente->SetDados('profissaomae', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissaomae']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('nomepai', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomepai']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('nascimentopai', converte_data($_POST[nascimentopai], 1));
			$paciente->SetDados('profissaopai', utf8_decode ( htmlspecialchars( utf8_encode($_POST['profissaopai']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('telefone1pais', $_POST[telefone1pais]);
			$paciente->SetDados('telefone2pais', $_POST[telefone2pais]);
			$paciente->SetDados('enderecofamiliar', $_POST[enderecofamiliar]);
			$paciente->SetDados('datacadastro', converte_data($_POST[datacadastro], 1));
			$paciente->SetDados('dataatualizacao', date(Y.'-'.m.'-'.d));
			$paciente->SetDados('status', $_POST[status]);
			$paciente->SetDados('objetivo', utf8_decode ( htmlspecialchars( utf8_encode($_POST['objetivo']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('observacoes', utf8_decode ( htmlspecialchars( utf8_encode($_POST['observacoes']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ));
			$paciente->SetDados('codigo_convenio', $_POST[convenio]);
			$paciente->SetDados('outros', $_POST[outros]);
			$paciente->SetDados('matricula', $_POST[matricula]);
			$paciente->SetDados('titular', $_POST[titular]);
			$paciente->SetDados('validadeconvenio', $_POST[validadeconvenio]);
			if($_GET[acao] != "editar") {
                $paciente->SalvarNovo();
				$objetivo = new TExObjetivo();
				$objetivo->SetDados('codigo_paciente', $_POST['codigo']);
				$objetivo->SalvarNovo();
				$objetivo = new TInquerito();
				$objetivo->SetDados('codigo_paciente', $_POST['codigo']);
				$objetivo->SalvarNovo();
				$objetivo = new TAtestado();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$objetivo = new TReceita();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$objetivo = new TExame();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$objetivo = new TEncaminhamento();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$objetivo = new TLaudo();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$objetivo = new TAgradecimento();
				$objetivo->Codigo_Paciente = $_POST['codigo'];
				$objetivo->SalvarNovo();
				$strScrp = "Ajax('pacientes/gerenciar', 'conteudo', 'codigo=".$_POST[codigo]."&acao=editar')";
			}
			$paciente->Salvar();
		}
	}
	if($_GET[acao] == "editar") {
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$paciente->LoadPaciente($_GET[codigo]);
		$row = $paciente->RetornaTodosDados();
		$row[nascimento] = converte_data($row[nascimento], 2);
		$row[nascimentomae] = converte_data($row[nascimentomae], 2);
		$row[nascimentopai] = converte_data($row[nascimentopai], 2);
		$row[datacadastro] = converte_data($row[datacadastro], 2);
		$strCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
		$strLoCase = $LANG['patients']['editing'];
		$acao = '&acao=editar';
	} else {
		$strCase = $LANG['patients']['including'];
		$strLoCase = $LANG['patients']['including'];
		$row = $_POST;
		$row[nome] = $_POST[nom];
		if(!isset($_POST[codigo]) || $j == 0) {
			$row = "";
			$row[codigo] = $paciente->ProximoCodigo();
		} else {
			$row[codigo] = $_POST[codigo];
		}
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>
<link href="../css/smile.css" rel="stylesheet" type="text/css" />

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="100%">&nbsp;&nbsp;&nbsp;<img src="pacientes/img/pacientes.png" alt="<?=$LANG['patients']['manage_patients']?>"> <span class="h3"><?=$LANG['patients']['manage_patients']?> &nbsp;[<?=$strCase?>] </span></td>
    </tr>
  </table>
<div class="conteudo" id="table dados">
<br />
<?include('submenu.php')?>
<br>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">
    
    <tr>
      <td height="26"><?=$strLoCase.' '.$LANG['patients']['patient']?> </td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td width="602">
      <form id="form2" name="form2" method="POST" action="pacientes/incluir_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><?=$LANG['patients']['personal_information']?></legend>
        <table width="570" border="0" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td><?=$r[1]?>* <?=$LANG['patients']['clinical_sheet']?><br />
<?
	if($_GET[acao] == "editar") {
?>
              <input disabled value="<?=$row[codigo]?>" type="text" class="forms" id="codigo"<?/* onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='%2Bthis.value"*/?> />
              <input name="codigo" value="<?=$row[codigo]?>" type="hidden" class="forms" <?=$disable?> id="codigo"<?/* onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='%2Bthis.value"*/?> />
<?
    } else {
?>
              <input name="codigo" value="<?=$row[codigo]?>" type="text" class="forms" <?=$disable?> id="codigo"<?/* onblur="javascript:foto_frame.location.href='pacientes/fotos.php?codigo='%2Bthis.value"*/?> />
<?
    }
?>
            </td>
            <td>&nbsp;</td>
            <td width="150" rowspan="13" valign="top"><br />
            <iframe height="300" scrolling="No" width="150" name="foto_frame" id="foto_frame" src="pacientes/fotos.php?codigo=<?=$row[codigo]?><?=(($_GET[acao] != "editar")?'&disabled=yes':'')?>" frameborder="0"></iframe>
            </td>
          </tr>
          <tr>
            <td width="290"><?=$r[2]?>* <?=$LANG['patients']['name']?><br />
                <label>
                  <input name="nom" value="<?=$row[nome]?>" type="text" class="forms" <?=$disable?> id="nom" size="50" maxlength="80" />
                </label>
                <br />
            <label></label></td>
            <td width="130"><?=$r[3]?><?=$LANG['patients']['document1']?><br />
              <input name="cpf" value="<?=$row['cpf']?>" type="text" class="forms" <?=$disable?> id="cpf" maxlength="50" />
            </td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['document2']?><br />
              <input name="rg" value="<?=$row[rg]?>" type="text" class="forms" <?=$disable?> id="rg" /></td>
            <td><?=$LANG['patients']['relationship_status']?><br /><select name="estadocivil" class="forms" <?=$disable?> id="estadocivil">
<?
	$valores = array('solteiro' => $LANG['patients']['single'], 'casado' => $LANG['patients']['married'], 'divorciado' => $LANG['patients']['divorced'], 'viuvo' => $LANG['patients']['widowed']);
	foreach($valores as $chave => $valor) {
		if($row[estadocivil] == $chave) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>       
			 </select>            </td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['gender']?><br />
                <select name="sexo" class="forms" <?=$disable?> id="sexo">
<?
	$valores = array('Masculino' => $LANG['patients']['male'], 'Feminino' => $LANG['patients']['female']);
	foreach($valores as $chave => $valor) {
		if($row[sexo] == $chave) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>       
			 </select> </td>
            <td><?=$LANG['patients']['ethnicity']?><br /><select name="etnia" class="forms" <?=$disable?> id="etnia">
<?
	$valores = array('africano' => $LANG['patients']['african'], 'asiatico' => $LANG['patients']['asian'], 'caucasiano' => $LANG['patients']['caucasian'], 'latino' => $LANG['patients']['latin'], 'orientemedio' => $LANG['patients']['middle_eastern'], 'multietnico' => $LANG['patients']['multi_ethnic']);
	foreach($valores as $chave => $valor) {
		if($row[etnia] == $chave) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>       
			 </select>            </td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['profession']?><br />
              <input name="profissao" value="<?=$row[profissao]?>" type="text" class="forms" <?=$disable?> id="profissao" /></td>
            <td><?=$LANG['patients']['naturality']?><br />
              <input name="naturalidade" value="<?=$row[naturalidade]?>" type="text" class="forms" <?=$disable?> id="naturalidade" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['nationality']?><br />
              <input name="nacionalidade" value="<?=$row[nacionalidade]?>" type="text" class="forms" <?=$disable?> id="nacionalidade" /></td>
            <td><?=$LANG['patients']['birthdate']?><br />
              <input name="nascimento" value="<?=$row[nascimento]?>" type="text" class="forms" <?=$disable?> id="nascimento" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['address1']?><br />
              <input name="endereco" value="<?=$row[endereco]?>" type="text" class="forms" <?=$disable?> id="endereco" size="50" maxlength="150" /></td>
            <td><?=$LANG['patients']['address2']?><br />
              <input name="bairro" value="<?=$row[bairro]?>" type="text" class="forms" <?=$disable?> id="bairro" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['city']?><br />
                <input name="cidade" value="<?=$row[cidade]?>" <?=$disable?> type="text" class="forms" <?=$disable?> id="cidade" size="30" maxlength="50" />
              <br /></td>
            <td><?=$LANG['patients']['state']?><br />
                <input name="estado" value="<?=$row[estado]?>" <?=$disable?> type="text" class="forms" <?=$disable?> id="estado" maxlength="50" />
            </td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['country']?><br />
                <input name="pais" value="<?=$row[pais]?>" <?=$disable?> type="text" class="forms" <?=$disable?> id="pais" size="30" maxlength="50" />
              <br /></td>
            <td><?=$LANG['patients']['dead']?><br /><select name="falecido" class="forms" <?=$disable?> id="falecido">
<?
	$valores = array('Não' => $LANG['patients']['no'], 'Sim' => $LANG['patients']['yes']);
	foreach($valores as $chave => $valor) {
		if($row[falecido] == $chave) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>
			 </select>            </td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['zip']?><br />
              <input name="cep" value="<?=$row[cep]?>" type="text" class="forms" <?=$disable?> id="cep" size="10" maxlength="9" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td><?=$LANG['patients']['cellphone']?><br />
              <input name="celular" value="<?=$row[celular]?>" type="text" class="forms" <?=$disable?> id="celular" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['residential_phone']?><br />
              <input name="telefone1" value="<?=$row[telefone1]?>" type="text" class="forms" <?=$disable?> id="telefone1" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?=$LANG['patients']['comercial_phone']?><br />
              <input name="telefone2" value="<?=$row[telefone2]?>" type="text" class="forms" <?=$disable?> id="telefone2" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['hobby']?><br />
              <input name="hobby" value="<?=$row[hobby]?>" type="text" class="forms" <?=$disable?> id="hobby" size="50" /></td>
            <td><?=$LANG['patients']['indicated_by']?><br />
              <input name="indicadopor" value="<?=$row[indicadopor]?>" type="text" class="forms" <?=$disable?> id="indicacao" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['email']?><br />
              <input name="email" value="<?=$row[email]?>" type="text" class="forms" <?=$disable?> id="email" size="50" /></td>
            <td><?=$LANG['patients']['comments_for_label']?> <br />
              <input name="obs_etiqueta" value="<?=$row[obs_etiqueta]?>" type="text" class="forms" <?=$disable?> id="obs_etiqueta" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
        <br /> <fieldset>
        <legend><span class="style1"><?=$LANG['patients']['treatments_to_do']?></span></legend>

        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input name="tratamento[]" value="Ortodontia" <?=((strpos($row[tratamento], 'Ortodontia')!== false)?'checked':'')?> type="checkbox" id="tra1" /><label for="tra1"> <?=$LANG['patients']['orthodonty']?></label></td>
            <td><input name="tratamento[]" value="Implantodontia" <?=((strpos($row[tratamento], 'Implantodontia')!== false)?'checked':'')?> type="checkbox" id="tra2" /><label for="tra2"> <?=$LANG['patients']['implantodonty']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Dentística" <?=((strpos($row[tratamento], 'Dentística')!== false)?'checked':'')?> type="checkbox" id="tra3" /><label for="tra3"> <?=$LANG['patients']['dentistic']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Prótese" <?=((strpos($row[tratamento], 'Prótese')!== false)?'checked':'')?> type="checkbox" id="tra4" /><label for="tra4"> <?=$LANG['patients']['prosthesis']?></label><br /></td>
          </tr>
          <tr>
            <td><input name="tratamento[]" value="Odontopediatria" <?=((strpos($row[tratamento], 'Odontopediatria')!== false)?'checked':'')?> type="checkbox" id="tra5" /><label for="tra5"> <?=$LANG['patients']['odontopediatry']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Cirurgia" <?=((strpos($row[tratamento], 'Cirurgia')!== false)?'checked':'')?> type="checkbox" id="tra6" /><label for="tra6"> <?=$LANG['patients']['surgery']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Endodontia" <?=((strpos($row[tratamento], 'Endodontia')!== false)?'checked':'')?> type="checkbox" id="tra7" /><label for="tra7"> <?=$LANG['patients']['endodonty']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Periodontia" <?=((strpos($row[tratamento], 'Periodontia')!== false)?'checked':'')?> type="checkbox" id="tra8" /><label for="tra8"> <?=$LANG['patients']['periodonty']?></label>&nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td><input name="tratamento[]" value="Radiologia" <?=((strpos($row[tratamento], 'Radiologia')!== false)?'checked':'')?> type="checkbox" id="tra9" /><label for="tra9"> <?=$LANG['patients']['radiology']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="DTM" <?=((strpos($row[tratamento], 'DTM')!== false)?'checked':'')?> type="checkbox" id="tra10" /><label for="tra10"> <?=$LANG['patients']['dtm']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Odontogeriatria" <?=((strpos($row[tratamento], 'Odontogeriatria')!== false)?'checked':'')?> type="checkbox" id="tra11" /><label for="tra11"> <?=$LANG['patients']['odontogeriatry']?></label>&nbsp;&nbsp;</td>
            <td><input name="tratamento[]" value="Ortopedia" <?=((strpos($row[tratamento], 'Ortopedia')!== false)?'checked':'')?> type="checkbox" id="tra12" /><label for="tra12"> <?=$LANG['patients']['orthopedy']?></label>&nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
         <br />
        <fieldset>
        <legend><span class="style1"><?=$LANG['patients']['professional_informations']?></span></legend>

        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="486"><?=$LANG['patients']['professional_searched']?><br />
                <label><select name="codigo_dentistaprocurado" class="forms" <?=$disable?> id="codigo_dentistaprocurado">
                <option></option>
<?
	$dentista = new TDentistas();
	$lista = $dentista->ListDentistas();
	for($i = 0; $i < count($lista); $i++) {
		if($row[codigo_dentistaprocurado] == $lista[$i][codigo]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].')</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
		}
	}
?>
			 </select>
                </label>
                <br />
                <br />
                <?=$LANG['patients']['answered_by']?><br />
                <label><select name="codigo_dentistaatendido" class="forms" <?=$disable?> id="codigo_dentistaatendido">
                <option></option>
<?
	$dentista = new TDentistas();
	$lista = $dentista->ListDentistas();
	for($i = 0; $i < count($lista); $i++) {
		if($row[codigo_dentistaatendido] == $lista[$i][codigo]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
		}
	}
?>
			 </select>
                </label>
                <br />
                <br />
                <?=$LANG['patients']['forwarded_to']?><br />
                <label><select name="codigo_dentistaencaminhado" class="forms" <?=$disable?> id="codigo_dentistaencaminhado">
                <option></option>
<?
	$dentista = new TDentistas();
	$lista = $dentista->ListDentistas();
	for($i = 0; $i < count($lista); $i++) {
		if($row[codigo_dentistaencaminhado] == $lista[$i][codigo]) {
			echo '<option value="'.$lista[$i][codigo].'" selected>'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
		} else {
			echo '<option value="'.$lista[$i][codigo].'">'.$lista[$i][titulo].' '.$lista[$i][nome].'</option>';
		}
	}
?>
			 </select>
                </label>
                <br />
            <label></label></td>
            <td width="11">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><br /></td>
          </tr>
        </table>
        </fieldset>
         <br />
        <fieldset>
        <legend><span class="style1"><?=$LANG['patients']['familiar_information']?></span></legend>

        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['father_name']?> <br />
                <input name="nomepai" value="<?=$row[nomepai]?>" type="text" class="forms" <?=$disable?> id="nomepai" size="50" maxlength="80" /></td>
            <td><?=$LANG['patients']['birthdate']?><br />
                <input name="nascimentopai" value="<?=$row[nascimentopai]?>" type="text" class="forms" <?=$disable?> id="nascimentopai" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['father_profession']?> <br />
                <input name="profissaopai" value="<?=$row[profissaopai]?>" type="text" class="forms" <?=$disable?> id="profissaopai" size="50" maxlength="80" /></td>
            <td><?=$LANG['patients']['telephone']?><br />
                <input name="telefone1pais" value="<?=$row[telefone1pais]?>" type="text" class="forms" <?=$disable?> id="telefone1pais" size="20" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td width="287"><br /><?=$LANG['patients']['mother_name']?><br />
                <label>
                <input name="nomemae" value="<?=$row[nomemae]?>" type="text" class="forms" <?=$disable?> id="nomemae" size="50" maxlength="80" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><br /><?=$LANG['patients']['birthdate']?><br />
                <input name="nascimentomae" value="<?=$row[nascimentomae]?>" type="text" class="forms" <?=$disable?> id="nascimentomae" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['mother_profession']?> <br />
                <input name="profissaomae" value="<?=$row[profissaomae]?>" type="text" class="forms" <?=$disable?> id="profissaomae" size="50" maxlength="80" /></td>
            <td><?=$LANG['patients']['telephone']?><br />
                <input name="telefone2pais" value="<?=$row[telefone2pais]?>" type="text" class="forms" <?=$disable?> id="telefone2pais" size="20" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td colspan="2"><br /><?=$LANG['patients']['complete_address_in_case_of_be_different_from_personal']?><br />
                <input name="enderecofamiliar" value="<?=$row[enderecofamiliar]?>" type="text" class="forms" <?=$disable?> id="endereco_familiar" size="78" maxlength="220" />                <br /></td>
          </tr>
        </table>
        </fieldset>
        <br />
        <fieldset>
        <legend><span class="style1"><?=$LANG['patients']['extra_information']?> </span></legend>

        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr>
            <td width="287"><?=$LANG['patients']['record_date']?>  <br />
                <label></label>
<?
	if($_GET[acao] == "editar") {
?>
                <input name="datacad" disabled value="<?=$row[datacadastro]?>" type="text" class="forms" <?=$disable?> id="datacad" size="20" maxlength="10" />
                <input name="datacadastro" value="<?=$row[datacadastro]?>" type="hidden" id="datacadastro" />
<?
	} else {
?>
                <input name="datacadastro" value="<?=date(d.'/'.m.'/'.Y)?>" type="text" class="forms" <?=$disable?> id="datacadastro" size="20" maxlength="10" onKeypress="return Ajusta_Data(this, event);" />
                <input name="datacad" value="" type="hidden" id="datacad" />
<?
	}
?>
                <br />
                <br />
                <label></label></td>
            <td width="210"><?=$LANG['patients']['last_update']?>  <br />
                <label></label>
                <input name="dataatua" disabled value="<?=converte_data($row[dataatualizacao], 2)?>" type="text" class="forms" <?=$disable?> id="dataatua" size="20" />
                <input name="dataatualizacao" value="<?=converte_data($row[dataatualizacao], 2)?>" type="hidden" id="dataatualizacao" />
                <br />
                <br />
                <label></label></td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['patients']['patient_status']?> <br />
              <label><select name="status" class="forms" <?=$disable?> id="status">
<?
	$valores = array('Evaluación' => $LANG['patients']['evaluation'], 'En tratamiento' => $LANG['patients']['in_treatment'], 'En revisión' => $LANG['patients']['in_revision'], 'Concluído' => $LANG['patients']['closed']);
	foreach($valores as $chave => $valor) {
		if($row[status] == $uf) {
			echo '<option value="'.$chave.'" selected>'.$valor.'</option>';
		} else {
			echo '<option value="'.$chave.'">'.$valor.'</option>';
		}
	}
?>       
			 </select> 
              <br />
              <br />
              </label></td>
            <td width="210"></td>
          </tr>
          <tr>
            <td><?=$LANG['patients']['main_objective_of_the_consultation']?><br />
              <label>
              <textarea name="objetivo" cols="25" rows="4"><?=$row[objetivo]?></textarea>
              </label></td>
            <td><?=$LANG['patients']['comments']?><br />
              <label>
              <textarea name="observacoes" cols="25" rows="4"><?=$row[observacoes]?></textarea>
              </label></td>
          </tr>
          <tr>
            <td><label></label></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>

         <fieldset>
        <legend><span class="style1"><?=$LANG['patients']['plan_information']?></span></legend>

        <table width="519" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="264"><?=$LANG['patients']['select_plan']?><br />
                <select name="convenio" class="forms" <?=$disable?> id="convenio">
<?
	$query1 = mysql_query("SELECT * FROM convenios ORDER BY nomefantasia");
	while($row1 = mysql_fetch_assoc($query1)) {
		if($row[codigo_convenio] == $row1['codigo']) {
			echo '<option value="'.$row1['codigo'].'" selected>'.$row1['nomefantasia'].'</option>';
		} else {
			echo '<option value="'.$row1['codigo'].'">'.$row1['nomefantasia'].'</option>';
		}
	}
?>       
			 </select> 
              <label><br />
              <br />
              </label></td>
            <td width="255"></td>
          </tr>
          <tr>
            <td><label><?=$LANG['patients']['card_number']?><br />
                <input name="matricula" value="<?=$row[matricula]?>" type="text" class="forms" <?=$disable?> id="matricula" size="20" />
                <br />
              </label></td>
            <td><?=$LANG['patients']['holder_name']?><br />
              <input name="titular" value="<?=$row[titular]?>" type="text" class="forms" <?=$disable?> id="titular" size="40" /></td>
          </tr>
          <tr>
            <td><br /><?=$LANG['patients']['good_thru']?> <br />
                <input name="validadeconvenio" value="<?=$row[validadeconvenio]?>" type="text" class="forms" <?=$disable?> id="validadeconvenio" size="20" />
                <br /></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>	
	
        <div align="center"><br />
          <input name="Salvar" type="submit" class="forms" <?=$disable?> id="Salvar" value="<?=$LANG['patients']['save']?>" />
        </div>
      </form>      </td>
    </tr>
    <tr>
      <td align="right">
        <img src="imagens/icones/imprimir.gif"> <a href="relatorios/paciente.php?codigo=<?=$row['codigo']?>" target="_blank"><?=$LANG['patients']['print_sheet']?></a>&nbsp;
      </td>
    </tr>
  </table>
<script>
document.getElementById('nom').focus();
</script>
