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
	if(($_GET['codigo'] != '' && !verifica_nivel('convenio', 'E')) || ($_GET['codigo'] == '' && !verifica_nivel('convenio', 'I'))) {
        $disable = 'disabled';
    }
	$convenio = new TConvenio();
	if(isset($_POST[Salvar])) {
		$obrigatorios[1] = 'nomefantasia';
		$i = $j = 0;
		foreach($_POST as $post => $valor) {
			$i++;
			if(array_search($post, $obrigatorios) && $valor == "") {
			    $j++;
				$r[$j] = '<font color="#FF0000">';
			}
		}
		if($j == 0) {
			if($_GET[acao] == "editar") {
				$convenio->LoadConvenio($_GET[codigo]);
				$strScrp = "Ajax('convenios/gerenciar', 'conteudo', '');";
			}
			$convenio->SetDados('nomefantasia', utf8_decode ( htmlspecialchars( utf8_encode($_POST['nomefantasia']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('cpf', $_POST[cpf]);
			$convenio->SetDados('razaosocial', utf8_decode ( htmlspecialchars( utf8_encode($_POST['razaosocial']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('atuacao', utf8_decode ( htmlspecialchars( utf8_encode($_POST['atuacao']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('endereco', utf8_decode ( htmlspecialchars( utf8_encode($_POST['endereco']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('bairro', utf8_decode ( htmlspecialchars( utf8_encode($_POST['bairro']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('cidade', utf8_decode ( htmlspecialchars( utf8_encode($_POST['cidade']) , ENT_QUOTES | ENT_COMPAT, 'utf-8') ) );
			$convenio->SetDados('estado', $_POST[estado]);
			$convenio->SetDados('pais', $_POST[pais]);
			$convenio->SetDados('cep', $_POST[cep]);
			$convenio->SetDados('celular', $_POST[celular]);
			$convenio->SetDados('telefone1', $_POST[telefone1]);
			$convenio->SetDados('telefone2', $_POST[telefone2]);
			$convenio->SetDados('inscricaoestadual', $_POST[inscricaoestadual]);
			$convenio->SetDados('website', $_POST[website]);
			$convenio->SetDados('email', $_POST[email]);
			$convenio->SetDados('nomerepresentante', $_POST[nomerepresentante]);
			$convenio->SetDados('apelidorepresentante', $_POST[apelidorepresentante]);
			$convenio->SetDados('emailrepresentante', $_POST[emailrepresentante]);
			$convenio->SetDados('celularrepresentante', $_POST[celularrepresentante]);
			$convenio->SetDados('telefone1representante', $_POST[telefone1representante]);
			$convenio->SetDados('telefone2representante', $_POST[telefone2representante]);
			$convenio->SetDados('banco', $_POST[banco]);
			$convenio->SetDados('agencia', $_POST[agencia]);
			$convenio->SetDados('conta', $_POST[conta]);
			$convenio->SetDados('favorecido', $_POST[favorecido]);
			if($_GET[acao] != "editar") {
				$convenio->SalvarNovo();
			}
			$convenio->Salvar();
    		$strScrp = "Ajax('convenios/gerenciar', 'conteudo', '');";
		}
	}
	if($_GET[acao] == "editar") {
		$strLoCase = $LANG['plan']['editing'];
		$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
		$convenio->LoadConvenio($_GET[codigo]);
		$row = $convenio->RetornaTodosDados();
	} else {
		if($j == 0) {
			$row = "";
		} else {
			$row = $_POST;
		}
		$strLoCase = $LANG['plan']['including'];
	}
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
?>
<div class="conteudo" id="conteudo_central">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
    <tr>
      <td width="56%">&nbsp;&nbsp;&nbsp;<img src="convenios/img/convenios.png" alt="<?=$LANG['plan']['manage_plans']?>"> <span class="h3"><?=$LANG['laboratory']['manage_plans']?> [<?=$strLoCase?>] </span></td>
      <td width="6%" valign="bottom"><a href="#"></a></td>
      <td width="36%" valign="bottom" align="right">&nbsp;</td>
      <td width="2%" valign="bottom">&nbsp;</td>
    </tr>
  </table>
<div class="conteudo" id="table dados"><br>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela_titulo">
    <tr>
      <td width="243" height="26"><?=$strLoCase.' '.$LANG['plan']['laboratory']?> </td>
      <td width="381">&nbsp;</td>
    </tr>
  </table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="convenios/incluir_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><fieldset>
        <legend><span class="style1"><?=$LANG['plan']['plan_information']?> </span></legend>
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?=$r[1]?>* <?=$LANG['plan']['company']?> <br />
                <label>
                  <input name="nomefantasia" value="<?=$row[nomefantasia]?>" <?=$disable?> type="text" class="forms" id="nomefantasia" size="50" maxlength="80" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><?=$LANG['plan']['document1']?><br />
              <input name="cpf" value="<?=$row[cpf]?>" <?=$disable?> type="text" class="forms" id="cpf" size="30" maxlength="18" onKeypress="return Ajusta_CPFCNPJ(this, event, document.getElementById('cpf_cnpj').value);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['legal_name']?><br />
              <input name="razaosocial" value="<?=$row[razaosocial]?>" <?=$disable?> type="text" class="forms" id="razaosocial" size="50" /></td>
            <td><?=$LANG['plan']['operation_area']?><br />
              <input name="atuacao" value="<?=$row[atuacao]?>" <?=$disable?> type="text" class="forms" id="atuacao" size="40" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['address1']?><br />
              <input name="endereco" value="<?=$row[endereco]?>" <?=$disable?> type="text" class="forms" id="endereco" size="50" maxlength="150" /></td>
            <td><?=$LANG['plan']['address2']?><br />
              <input name="bairro" value="<?=$row[bairro]?>" <?=$disable?> type="text" class="forms" id="bairro" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['city']?><br />
                <input name="cidade" value="<?=$row[cidade]?>" <?=$disable?> type="text" class="forms" id="cidade" size="30" maxlength="50" />
              <br /></td>
            <td><?=$LANG['plan']['state']?><br />
                <input name="estado" value="<?=$row[estado]?>" <?=$disable?> type="text" class="forms" id="estado" maxlength="50" />
            </td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['country']?><br />
                <input name="pais" value="<?=$row[pais]?>" <?=$disable?> type="text" class="forms" id="pais" size="30" maxlength="50" />
              <br /></td>
            <td>&nbsp;
            </td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['zip']?><br />
              <input name="cep" value="<?=$row[cep]?>" <?=$disable?> type="text" class="forms" id="cep" size="10" maxlength="9" onKeypress="return Ajusta_CEP(this, event);" /></td>
            <td><?=$LANG['plan']['cellphone']?><br />
              <input name="celular" value="<?=$row[celular]?>" <?=$disable?> type="text" class="forms" id="celular" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['phone1']?><br />
              <input name="telefone1" value="<?=$row[telefone1]?>" <?=$disable?> type="text" class="forms" id="telefone1" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?=$LANG['plan']['phone2']?><br />
              <input name="telefone2" value="<?=$row[telefone2]?>" <?=$disable?> type="text" class="forms" id="telefone2" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['document2']?><br />
              <input name="inscricaoestadual" value="<?=$row[inscricaoestadual]?>" <?=$disable?> type="text" class="forms" id="ie" size="25" /></td>
            <td><?=$LANG['plan']['website']?><br />
              <input name="website" value="<?=$row[website]?>" <?=$disable?> type="text" class="forms" id="site" size="40" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['email']?><br />
              <input name="email" value="<?=$row[email]?>" <?=$disable?> type="text" class="forms" id="email" size="40" /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?=$LANG['plan']['representative_information_contact_person']?></span></legend>
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['representative_name_contact_person']?><br />
                <label>
                  <input name="nomerepresentante" value="<?=$row[nomerepresentante]?>" <?=$disable?> type="text" class="forms" id="nome" size="50" maxlength="80" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><?=$LANG['plan']['nickname']?><br />
              <input name="apelidorepresentante" value="<?=$row[apelidorepresentante]?>" <?=$disable?> type="text" class="forms" id="apelido" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['email']?><br />
                <input name="emailrepresentante" value="<?=$row[emailrepresentante]?>" <?=$disable?> type="text" class="forms" id="email" size="50" maxlength="100" /></td>
            <td><?=$LANG['plan']['cellphone']?><br />
                <input name="celularrepresentante" value="<?=$row[celularrepresentante]?>" <?=$disable?> type="text" class="forms" id="celularrep" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['phone1']?><br />
                <input name="telefone1representante" value="<?=$row[telefone1representante]?>" <?=$disable?> type="text" class="forms" id="telefonerep" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
            <td><?=$LANG['plan']['phone2']?><br />
                <input name="telefone2representante" value="<?=$row[telefone2representante]?>" <?=$disable?> type="text" class="forms" id="telefone1rep" maxlength="13" onKeypress="return Ajusta_Telefone(this, event);" /></td>
          </tr>
          
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?=$LANG['plan']['bank_information']?></span></legend>
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['bank']?> <br />
                <label>
                  <input name="banco1" value="<?=$row['banco1']?>" <?=$disable?> type="text" class="forms" id="banco1" size="50" maxlength="50" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><br />
              </td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['agency']?><br />
                <input name="agencia1" value="<?=$row['agencia1']?>" <?=$disable?> type="text" class="forms" id="agencia1" size="50" maxlength="15" /></td>
            <td><?=$LANG['plan']['account']?><br />
                <input name="conta1" value="<?=$row['conta1']?>" <?=$disable?> type="text" class="forms" id="conta1" maxlength="15" /></td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['account_holder']?><br />
                <label>
                  <input name="favorecido1" value="<?=$row['favorecido1']?>" <?=$disable?> type="text" class="forms" id="favorecido1" size="50" maxlength="50" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><br />
              </td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['bank']?> <br />
                <label>
                  <input name="banco2" value="<?=$row['banco2']?>" <?=$disable?> type="text" class="forms" id="banco2" size="50" maxlength="50" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><br />
              </td>
          </tr>
          <tr>
            <td><?=$LANG['plan']['agency']?><br />
                <input name="agencia2" value="<?=$row['agencia2']?>" <?=$disable?> type="text" class="forms" id="agencia2" size="50" maxlength="15" /></td>
            <td><?=$LANG['plan']['account']?><br />
                <input name="conta2" value="<?=$row['conta2']?>" <?=$disable?> type="text" class="forms" id="conta2" maxlength="15" /></td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['account_holder']?><br />
                <label>
                  <input name="favorecido2" value="<?=$row['favorecido2']?>" <?=$disable?> type="text" class="forms" id="favorecido2" size="50" maxlength="50" />
                </label>
                <br />
                <label></label></td>
            <td width="210"><br />
              </td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
        <br />
		<fieldset>
        <legend><span class="style1"><?=$LANG['plan']['comments']?></span></legend>
        <table width="497" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="287"><?=$LANG['plan']['comments']?> <br />
                  <textarea name="observacoes" <?=$disable?> class="forms" id="observacoes" cols="50" rows="8"><?=$row['observacoes']?></textarea>
                <br />
                <label></label></td>
            <td width="210"><br />
              </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        </fieldset>
		<br />
        <div align="center"><br />
          <input name="Salvar" type="submit" <?=$disable?> class="forms" id="Salvar" value="<?=$LANG['plan']['save']?>" />
        </div>
      </form>      </td>
    </tr>
  </table>
</div>
<script>
  document.getElementById('nomefantasia').focus();
</script>
