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
    if(!isset($_GET['idioma'])) {
        $_GET['idioma'] = 'pt_br';
    }
    require_once('lang/'.$_GET['idioma'].'.php');
    function extraiSQL($arquivo) {
        $file = file($arquivo);
        $sql = array();
        $j = 0;
        $sql[$j] = "";
        for($i = 0; $i < count($file); $i++) {
            $file[$i] = trim($file[$i]);
            if(substr($file[$i], 0, 1) != '#' && substr($file[$i], 0, 2) != '--') {
                $sql[$j] .= $file[$i];
            }
            if(substr($file[$i], -1) == ';' ||substr($file[$i], -2) == '$$') {
                $sql[$j] = trim($sql[$j], " ;\n");
                $j++;
                $sql[$j] = "";
            }
        }
        array_pop($sql);
        return($sql);
    }
    $caminho = 'lib/config.inc.php';
    require_once ( $caminho );
    if(isset($_POST['send'])) {
        if(is_writable($caminho)) {
            $conn = @mysql_connect($_POST['server'], $_POST['user'], $_POST['pass']);
            $myerro = 0;
            if(mysql_errno() == 1045) {
                $myerro++;
                $r[4] = ' color="#FF0000"';
                $msg[] = $LANG['config']['err_access_denied_to_the_database_server'];
                //Acesso negado
            } elseif(mysql_errno() == 2005) {
                $myerro++;
                $r[3] = ' color="#FF0000"';
                $msg[] = $LANG['config']['err_database_server_not_found'];
                //Servidor não encontrado
            }
            if($_POST['versao'] == 'novo') {
                if(!empty($_POST['senha']) && !empty($_POST['fantasia']) && $_POST['senha'] == $_POST['resenha'] && strlen($_POST['senha']) >= 6 && $myerro === 0) {
                    $config = file($caminho);
                    $config[51] = "    \$server = '".$_POST['server']."';\n";
                    $config[52] = "    \$user = '".$_POST['user']."';\n";
                    $config[53] = "    \$pass = '".$_POST['pass']."';\n";
                    $config[54] = "    \$bd = '".$_POST['bd']."';\n";
                    $config[64] = "    \$install = true;\n";
                    file_put_contents($caminho, $config);
                    mysql_query("CREATE DATABASE IF NOT EXISTS ".$_POST['bd']) or die('Criação da base: '.mysql_error());
                    mysql_select_db($_POST['bd'], $conn) or die('Seleção da base: '.mysql_error());
                    $sqls = extraiSQL('bases/bd_novo.sql');
                    foreach($sqls as $sql) {
                        mysql_query($sql) or die('Importação nova: '.mysql_error());
                    }
                    $sql = "UPDATE funcionarios SET senha = MD5('".$_POST['senha']."') WHERE cpf = '11111111111'";
                    mysql_query($sql) or die('Alteração de senha: '.mysql_error());
                    $sql  = "UPDATE dados_clinica SET cnpj = '".$_POST['cnpj']."', razaosocial = '".$_POST['razaosocial']."', fantasia = '".$_POST['fantasia']."', proprietario = '".$_POST['proprietario']."', endereco = '".$_POST['endereco']."', ";
                    $sql .= "bairro = '".$_POST['bairro']."', cidade = '".$_POST['cidade']."', estado = '".$_POST['estado']."', cep = '".$_POST['cep']."', fundacao = '".$_POST['fundacao']."', telefone1 = '".$_POST['telefone1']."', telefone2 = '".$_POST['telefone2']."', ";
                    $sql .= "fax = '".$_POST['fax']."', email = '".$_POST['email']."', web = '".$_POST['web']."', idioma = '".$_GET['idioma']."'";
                    mysql_query($sql) or die('Alteração de dados da clínica: '.mysql_error());
                    header('Location: ./');
                } else {
                    if(empty($_POST['senha']) || $_POST['senha'] != $_POST['resenha'] || strlen($_POST['senha']) < 6) {
                        $r[0] = ' color="#FF0000"';
                        $msg[] = $LANG['config']['err_password_must_have_at_least_6_characters_and_must_be_retyped'];
                    }
                    if(empty($_POST['fantasia'])) {
                        $r[1] = ' color="#FF0000"';
                        $msg[] = $LANG['config']['err_name_shall_be_filled'];
                    }
                }
            } else {
                if($myerro === 0) {
                    mysql_select_db($_POST['bd'], $conn) or die('Seleção da base: '.mysql_error());
                    $sqls = extraiSQL('bases/bd_atu_'.$_POST['versao'].'.sql');
                    foreach($sqls as $sql) {
                        mysql_query($sql) or die('Importação Atualização: '.mysql_error().' - '.$sql);
                    }
                    $config = file($caminho);
                    $config[51] = "    \$server = '".$_POST['server']."';\n";
                    $config[52] = "    \$user = '".$_POST['user']."';\n";
                    $config[53] = "    \$pass = '".$_POST['pass']."';\n";
                    $config[54] = "    \$bd = '".$_POST['bd']."';\n";
                    $config[64] = "    \$install = true;\n";
                    file_put_contents($caminho, $config);
                    header('Location: ./');
                }
            }
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GCO :: <?=$LANG['config']['configuration_wizard']?></title>
<script language="javascript" type="text/javascript" src="lib/script.js"></script>
<link href="css/smile.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
-->
</style></head>

<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><img src="imagens/top_gerenciador_smile.jpg" alt="Topo Gerenciador" width="770" height="40" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="conteudo">
      <tr>
        <td width="74%">&nbsp;&nbsp;&nbsp;<img src="sobre/img/sobre.png" alt="Sobre" /> <span class="h3"><?=$LANG['config']['configuration_wizard']?></span></td>
        <td width="7%" valign="bottom">&nbsp;</td>
        <td width="19%" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <form method="POST" action="configurador.php?idioma=<?=$_GET['idioma']?>">
    <div class="conteudo" id="table dados">
<?php
    if(count($msg) > 0) {
?>
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?=$LANG['config']['errors']?></strong></legend>
          <p><?=$LANG['config']['below_follow_the_errors_found_in_the_system']?></p>
            <p align="left">
<?php
    foreach($msg as $erro) {
        echo '&nbsp;&nbsp;- '.$erro."<br />\n";
    }
?>
            </p>
        </fieldset>
      </div>
<?php
    }
?>
<br />
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?=$LANG['config']['language']?></strong></legend>
          <p><?=$LANG['config']['select_your_language']?></p>
            <p align="center">
<?php
    $handle = opendir('./lang');
    while ($file = readdir($handle)) {
        if(strpos($file, '.') !== 0) {
            $nome_file = explode('.', $file);
            $idiomas[] = '<a href="?idioma='.$nome_file[0].'">'.$nome_file[0].'</a>';
        }
	}
    $idiomas = implode(' | ', $idiomas);
    echo $idiomas;
?>
            </p>
        </fieldset>
      </div>
<br />
      <div class="sobre" id="div5">
        <fieldset>
        <legend><strong><?=$LANG['config']['initial_information']?></strong></legend>
          <p><?=$LANG['config']['wellcome']?><br />
            <br />
            <?=$LANG['config']['in_case_unix']?>:<br />
            /configurador.php<?=is_writable('configurador.php')?' - <i><font color="#009900">'.$LANG['config']['you_have_pemission_to_write_in_this_file'].'</font></i>':' - <i><font color="#FF0000">'.$LANG['config']['you_dont_have_permission_to_write_in_this_files'].'</font></i>'?><br />
            /lib/config.inc.php<?=is_writable('lib/config.inc.php')?' - <i><font color="#009900">'.$LANG['config']['you_have_pemission_to_write_in_this_file'].'</font></i>':' - <i><font color="#FF0000">'.$LANG['config']['you_dont_have_permission_to_write_in_this_files'].'</font></i>'?></p>
          <p><?=$LANG['config']['in_case_windows']?><br />
            </p>
        </fieldset>
      </div>
<br />
      <div class="sobre" id="sobre">
            
            <fieldset>
            <legend><strong><?=$LANG['config']['installation_type']?> </strong></legend>
            <p><?=$LANG['config']['is_it_your_first_time']?></p>
            <p align="center">
              <select name="versao" id="versao" class="forms" onchange="if(this.selectedIndex==0) {document.getElementById('info').style.display='block'} else {document.getElementById('info').style.display='none'}">
<?
    $versoes = array();
    $versoes[] = $LANG['config']['new_installation'];
    $versoes[] = $LANG['config']['update_from_0_18_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_1_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_2_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_2_2_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_3_0_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_3_5_to_current'] . ' ' . $version;
    $versoes[] = $LANG['config']['update_from_4_0_to_current'] . ' ' . $version;
    $valores = array();
    $valores[] = 'novo';
    $valores[] = '0_18';
    $valores[] = '1_0';
    $valores[] = '2_0';
    $valores[] = '2_2';
    $valores[] = '3_0';
    $valores[] = '3_5';
    $valores[] = '4_0';
    for($i=0; $i<count($versoes); $i++) {
        echo '<option value="'.$valores[$i].'"'.(($_POST['versao'] == $valores[$i])?' selected':'').'>'.$versoes[$i].'</option>';
    }
?>
              </select>
              <br />
            </p>
          </fieldset></div>
            <br />
            <div class="sobre" id="mysql">
              <fieldset>
              <legend><strong><?=$LANG['config']['mysql_information']?> </strong> </legend>
                <p><?=$LANG['config']['to_install_gco']?></p>
                <p align="center"><span class="texto"><font<?=$r[3]?>><?=$LANG['config']['server']?></font><br />
                  <input name="server" type="text" class="forms" id="server" value="<?=((empty($_POST['server']))?'localhost':$_POST['server'])?>" />
                  <br />
                  <br />
                  <?=$LANG['config']['database']?><br />
                  <input name="bd" type="text" class="forms" id="bd" value="<?=((empty($_POST['bd']))?'gerenciador':$_POST['bd'])?>" />
                  <br />
                  <br />
                  <font<?=$r[4]?>><?=$LANG['config']['user']?></font><br />
                  <input name="user" type="text" class="forms" id="user" value="<?=((empty($_POST['user']))?'root':$_POST['user'])?>" />
                  <br />
                  <br />
                  <font<?=$r[4]?>><?=$LANG['config']['password']?></font><br />
                  <input name="pass" type="text" class="forms" id="pass" value="<?=((empty($_POST['pass']))?'':$_POST['pass'])?>" />
                  <br /></span>
              </p>
              </fieldset>
            </div>
			<br />
          <div class="sobre" id="info">
            <fieldset>
              <legend><strong><?=$LANG['config']['admin_information']?></strong> </legend>
                <p><?=$LANG['config']['the_admin_user']?></p>
              <p align="center"><?=$LANG['config']['username']?>: <strong>admin </strong><br />
                <br />
                  <br />
                  <span class="texto"><font<?=$r[0]?>>* <?=$LANG['config']['new_password']?>:</font><br />
                  <input name="senha" type="password" class="forms" id="senha" />
                  <br />
                  <br />
                  <font<?=$r[0]?>>* <?=$LANG['config']['retype_new_password']?>: </font><br />
                  <input name="resenha" type="password" class="forms" id="resenha" />
                  <br /></span>
            </p>
            </fieldset>
			<br />
              <fieldset>
              <legend><strong><?=$LANG['config']['clinic_information']?></strong> </legend>
                <p><?=$LANG['config']['as_it_is_your_first']?></p>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="texto">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><span class="texto"><font<?=$r[0]?>>* <?=$LANG['config']['company_name']?></font><br />
                      <label>
                      <input name="fantasia" value="<?=$_POST['fantasia']?>" type="text" class="forms" id="fantasia" size="45" maxlength="80" />
                      </label>
                      <br />
                      <label></label></td>
                    <td width="210"><font<?=$r[2]?> class="texto">
                        <?=$LANG['config']['document1']?></font>
                      <br />
                      <input name="cnpj" value="<?=$_POST['cnpj']?>" type="text" class="forms" id="cnpj" size="30" maxlength="18" />
                    </td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['legal_name']?> <br />
                        <input name="razaosocial" value="<?=$_POST['razaosocial']?>" type="text" class="forms" id="razaosocial" size="45" /></td>
                    <td><?=$LANG['config']['owner']?><br />
                      <input name="proprietario" value="<?=$_POST['proprietario']?>" type="text" class="forms" id="proprietario" size="40" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['address1']?><br />
                        <input name="endereco" value="<?=$_POST['endereco']?>" type="text" class="forms" id="endereco" size="45" maxlength="150" /></td>
                    <td><?=$LANG['config']['address2']?><br />
                        <input name="bairro" value="<?=$_POST['bairro']?>" type="text" class="forms" id="bairro" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['city']?><br />
                        <input name="cidade" value="<?=$_POST['cidade']?>" type="text" class="forms" id="cidade" size="30" maxlength="50" />
                        <br /></td>
                    <td><?=$LANG['config']['state']?><br />
                        <input name="estado" value="<?=$_POST['estado']?>" type="text" class="forms" id="estado" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['country']?><br />
                        <input name="pais" value="<?=$_POST['pais']?>" type="text" class="forms" id="pais" size="30" maxlength="50" />
                        <br /></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['zip']?><br />
                        <input name="cep" value="<?=$_POST['cep']?>" type="text" class="forms" id="cep" size="10" maxlength="9" onkeypress="return Ajusta_CEP(this, event);" /></td>
                    <td><?=$LANG['config']['year_of_foundation']?><br />
                        <input name="fundacao" value="<?=$_POST['fundacao']?>" type="text" class="forms" id="fundacao" maxlength="4" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['phone1']?><br />
                        <input name="telefone1" value="<?=$_POST['telefone1']?>" type="text" class="forms" id="telefone1" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                    <td><?=$LANG['config']['phone_2']?><br />
                        <input name="telefone2" value="<?=$_POST['telefone2']?>" type="text" class="forms" id="telefone2" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['fax']?> <br />
                        <input name="fax" value="<?=$_POST['fax']?>" type="text" class="forms" id="fax" size="25" maxlength="13" onkeypress="return Ajusta_Telefone(this, event);" /></td>
                    <td><?=$LANG['config']['website']?><br />
                        <input name="web" value="<?=$_POST['web']?>" type="text" class="forms" id="web" size="40" /></td>
                  </tr>
                  <tr>
                    <td><?=$LANG['config']['email']?><br />
                        <input name="email" value="<?=$_POST['email']?>" type="text" class="forms" id="email" size="40" /></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <p align="left"><?=$LANG['config']['to_more_customization']?><br />
                </p>
              </fieldset>
            </div>
            <br />
                      <div class="sobre" id="div4">
                        <fieldset>
                        <legend><strong><?=$LANG['config']['finishing_and_saving_information']?> </strong> </legend>
                          <p><?=$LANG['config']['after_filling_all_fields']?></p>
                          <p><?=$LANG['config']['installing_gco_you_agree']?></p><br />

                          <p align="center">                            
                            <input name="send" type="submit" class="forms" id="enviar" value="<?=$LANG['config']['i_agree_install_gco_smile']?>" />
                            
                        </p>
                        </fieldset>
                      </div>
    </div>
    </div></form></td>
  </tr>
</table>

<script>
if(document.getElementById('versao').selectedIndex==0) {
    document.getElementById('info').style.display='block';
} else {
    document.getElementById('info').style.display='none';
}
</script>
</body>
</html>
