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
	$paciente = new TEvolucao();
	if($_GET['confirm_del'] == 'delete' && $_GET['codigo_evolucao'] != '') {
        $paciente->LoadEvolucao($_GET['codigo_evolucao']);
        $paciente->ApagaDados();
	}
	if(isset($_POST[Salvar])) {
		/*if(is_array($_POST[procexecutado])) {
			foreach($_POST[procexecutado] as $codigo => $procexecutado) {
				$procprevisto = $_POST[procprevisto][$codigo];
				$codigo_dentista = $_POST[codigo_dentista][$codigo];
				$data = converte_data($_POST[data][$codigo], 1);
				$paciente->LoadEvolucao($codigo);
				$paciente->SetDados('procexecutado', $procexecutado);
				$paciente->SetDados('procprevisto', $procprevisto);
				$paciente->SetDados('codigo_dentista', $codigo_dentista);
				$paciente->SetDados('data', $data);
				$paciente->Salvar();
			}
		}*/
		if(!empty($_POST[procexecutado_new]) && !empty($_POST[procprevisto_new]) && !empty($_POST[data_new])) {
			$paciente->SetDados('codigo_paciente', $_GET[codigo]);
			$paciente->SetDados('procexecutado', $_POST[procexecutado_new]);
			$paciente->SetDados('procprevisto', $_POST[procprevisto_new]);
			$paciente->SetDados('codigo_dentista', $_POST[codigo_dentista_new]);
			$paciente->SetDados('data', converte_data($_POST[data_new], 1));
			$paciente->SalvarNovo();
			$paciente->Salvar();
		}
	}
	$frmActEdt = "?acao=editar&codigo=".$_GET[codigo];
	$acao = '&acao=editar';
	$strLoCase = encontra_valor('pacientes', 'codigo', $_GET[codigo], 'nome').' - '.$_GET['codigo'];
	if(isset($strScrp)) {
		echo '<scr'.'ipt>'.$strScrp.'</scr'.'ipt>';
		die();	
	}
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
      <td height="26">&nbsp;<?=$LANG['patients']['treatment_evolution']?> </td>
    </tr>
  </table>
  <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="tabela">
    <tr>
      <td>
      <form id="form2" name="form2" method="POST" action="pacientes/evolucao_ajax.php<?=$frmActEdt?>" onsubmit="formSender(this, 'conteudo'); return false;"><br /><fieldset>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="texto">
          <tr>
            <td width="23%" height="20" bgcolor="#0099CC"><div align="center" class="style4"><?=$LANG['patients']['executed_procedure']?></div></td>
            <td width="2%" bgcolor="#0099CC"><div align="center" class="style4">&nbsp;</div></td>
            <td width="23%" bgcolor="#0099CC"><div align="center" class="style4"><?=$LANG['patients']['previwed_procedure']?></div></td>
            <td width="23%" bgcolor="#0099CC"><div align="center" class="style4"><?=$LANG['patients']['professional']?> </div></td>
            <td width="14%" bgcolor="#0099CC"><div align="center" class="style4"><?=$LANG['patients']['date']?></div></td>
            <td width="6%" bgcolor="#0099CC"><div align="center" class="style4"><?=$LANG['patients']['delete']?></div></td>
          </tr>
<?
	$paciente->SetDados('codigo_paciente', $_GET[codigo]);
	$lista = $paciente->ListEvolucao();
	if(is_array($lista)) {
		foreach($lista as $chave => $codigo) {
			$paciente->LoadEvolucao($codigo);
			if($chave % 2 == 0) {
				$td = 'td_even';
			} else {
				$td = 'td_odd';
			}
?>
          <tr class="<?=$td?>">
            <td height="23"><div align="left"><input type="text" value="<?=$paciente->RetornaDados('procexecutado')?>" size="25" class="forms" id="procexecutado<?=$chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?=$paciente->RetornaDados('codigo')?>&procexecutado='%2Bthis.value%2B'&procprevisto='%2Bdocument.getElementById('procprevisto<?=$chave?>').value%2B'&data='%2Bdocument.getElementById('data<?=$chave?>').value)" <?=((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?=$disable?> /></div></td>
            <td></td>
            <td><div align="left"><input type="text" value="<?=$paciente->RetornaDados('procprevisto')?>" size="25" class="forms" id="procprevisto<?=$chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?=$paciente->RetornaDados('codigo')?>&procexecutado='%2Bdocument.getElementById('procexecutado<?=$chave?>').value%2B'&procprevisto='%2Bthis.value%2B'&data='%2Bdocument.getElementById('data<?=$chave?>').value)" <?=((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?=$disable?> /></div></td>
            <td><div align="left">
<?
			$dentista = new TDentistas();
			$lista = $dentista->LoadDentista($paciente->RetornaDados('codigo_dentista'));
			$nome = explode(' ', $dentista->RetornaDados('nome'));
			$nome = $nome[0].' '.$nome[count($nome) - 1];
			echo $dentista->RetornaDados('titulo').' '.$nome;
?>
            </td>
            <td><div align="center"><input type="text" value="<?=converte_data($paciente->RetornaDados('data'), 2)?>" class="forms" id="data<?=$chave?>" onblur="Ajax('pacientes/atualiza_evolucao', 'evolucao_atualiza', 'codigo=<?=$paciente->RetornaDados('codigo')?>&procexecutado='%2Bdocument.getElementById('procexecutado<?=$chave?>').value%2B'&procprevisto='%2Bdocument.getElementById('procprevisto<?=$chave?>').value%2B'&data='%2Bthis.value)" <?=((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'':'readonly="readonly"')?> <?=$disable?> size="12" maxlength="10" onKeypress="return Ajusta_Data(this, event);" /></div></td>
            <td><div align="center"><?=((checknivel('Administrador') || (checknivel('Dentista') && $_SESSION['codigo'] == $paciente->RetornaDados('codigo_dentista')))?'<a href="javascript:Ajax(\'pacientes/evolucao\', \'conteudo\', \'codigo='.$_GET['codigo'].'&acao=editar&codigo_evolucao='.$paciente->RetornaDados('codigo').'" onclick="return confirmLink(this)"><img src="imagens/icones/excluir.gif" alt="" width="19" height="19" border="0"></a>':'')?></div></td>
          </tr>
<?
		}
	}
	if($td == "td_odd") {
		$td = 'td_even';
	} else {
		$td = 'td_odd';
	}
?>
          <tr class="<?=$td?>">
            <td><div align="left">
              <input name="procexecutado_new" id="procexecutado_new" type="text" class="forms" size="25" <?=$disable?> />
            </div></td>
            <td></td>
            <td><div align="left">
              <input name="procprevisto_new" type="text" class="forms" size="25" <?=$disable?> />
            </div></td>
            <td><div align="left"><select name="codigo_dentista_new" class="forms" style="width: 150px" <?=$disable?>>
                <option></option>
<?
			$dentista = new TDentistas();
			$lista = $dentista->ListDentistas("SELECT * FROM `dentistas` WHERE `ativo` = 'Sim' ORDER BY `nome` ASC");
			for($i = 0; $i < count($lista); $i++) {
				$nome = explode(' ', $lista[$i][nome]);
				$nome = $nome[0].' '.$nome[count($nome) - 1];
				if(((checknivel('Administrador') || checknivel('Funcionario')) || (checknivel('Dentista') && $_SESSION['codigo'] == $lista[$i]['codigo']))) {
				    echo '<option value="'.$lista[$i][codigo].'" '.(($_SESSION['codigo'] == $lista[$i]['codigo'] && checknivel('Dentista'))?'selected':'').'>'.$lista[$i][titulo].' '.$nome.'</option>';
                }
			}
?>       
			 </select></td>
            <td><div align="center">
              <input name="data_new" type="text" class="forms" value="<?=date(d.'/'.m.'/'.Y)?>" size="12" maxlength="10" onKeypress="return Ajusta_Data(this, event);" <?=$disable?> />
            </div></td>
            <td><div align="center"></div></td>
          </tr>
        </table>
        <br />
      </fieldset>
        <br />
        <div align="center">
          <input name="Salvar" type="submit" class="forms" id="Salvar" value="<?=$LANG['patients']['save']?>" <?=$disable?> />
      </form>
       </div>
       <p align="center"><a href="relatorios/evolucao.php?codigo=<?=$_GET['codigo']?>" target="_blank"><?=$LANG['patients']['print_evolution']?></a></p>
      &nbsp;
      </td>
    </tr>
  </table>
  <div id="evolucao_atualiza">&nbsp;</div>
<script>
document.getElementById('procexecutado_new').focus();
</script>
