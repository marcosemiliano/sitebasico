<?php
$host="localhost"; $dbname="sitecriando"; $user="root"; $senha=""; $porta="3306";
$dbm = mysqli_connect("$host", "$user", "$senha", "$dbname") or die ("Problemas para Conectar no Banco de Dados MariaDB.<br>");



mysqli_query($dbm,"SET NAMES 'utf8'");
mysqli_query($dbm,'SET character_set_connection=utf8');
mysqli_query($dbm,'SET character_set_client=utf8');
mysqli_query($dbm,'SET character_set_results=utf8');



printf("<!DOCTYPE html>\n");
printf("<html>\n");
printf("  <head>\n");
printf("    <meta charset='utf-8'>\n");
printf("  <title>teste</title>\n");
printf("  </head>\n");
printf("  <body bgcolor='#FFDEAD'>\n");







require_once("./cadastrar.php");
$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
$acao = ( ISSET($_POST['acao'])  ) ? $_POST['acao'] : "Abertura";
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');

switch (TRUE)
{ # 1 - divisor principal de comandos com referecnia na variável $acao-------------------------------------------------------------------------------
  case ($acao=='Abertura'):
  { # 1.1 - funcionaliade: abertura do programa -----------------------------------------------------------------------------------------------------
    printf("Este &eacute; o sistema de programas de Gerenciamento de seguros - vers&atilde;o compacta - para o SGBD PostgreSQL<br><br>\n");
    printf("Use o Menu acima para escolher as a&ccedil;&otilde;es que deseja realizar sobre os dados da tabela.<br>\n");
    printf("Para cada a&ccedil;&atilde;o disparada uma nova tela se abre neste painel (inferior).<br>\n");
    printf("Nesta nova tela, na &uacute;ltima linha (escrita centralizada na tela), no lado esquerdo surge a fun&ccedil;&atilde;o executada e no lado direito o c&oacute;digo do programa em execu&ccedil;&atilde;o.<br>\n");
    printf("Isso ajuda muito na hora de localizar eventuais erros no programa.<br><br>\n");
    printf("Se um erro ocorrer no uso do Programa entre em contato com o Suporte t&eacute;cnico informando a mensagem de erro e o c&oacute;digo do programa.<br><br>\n");
    printf("Este sistema foi desenvolvido por (Marcos emiliano de luca Gonçalves) para contar como um dos trabalhos da disciplina.<br><br>\n");
    printf("Laborat&oacute;rio de Banco de Dados do curso de An&aacute;lise e Desenvolvimento de sistemas da FATEC de Ourinhos.<br><br>\n");
    break;
  }
case ($acao=='cadastrar'):
  { # 1.2 - funcionalidade: Incluir -----------------------------------------------------------------------------------------------------------------
    # Desvio de Blocos Principais baseado em $bloco. ------------------------------------------------------------------------------------------------
    SWITCH (TRUE)
    { # 1.2.1-montando a tela de form para digitação dos dados para inclusão ------------------------------------------------------------------------
      case ( $bloco==1 ):
      { # 1.2.1.1-Bloco para montagem do Formulário para entrada de dados. --------------------------------------------------------------------------
        # Montando o form de leitura dos dados dos campos da tabela (os campos FORM terão os mesmos NOMES dos campos da tabela.
        # Aqui vamos montar um form 'passando' o valor $ bloco para 2.
        setupform($acao,$bloco,$salto);
        break;
      } # 1.2.1.1-Fim do Bloco que monta o form de entrada de dados ---------------------------------------------------------------------------------
      case ( $bloco==2 ):
      { # 1.2.1.2-Bloco para Tratamento da Transação ------------------------------------------------------------------------------------------------
        # Alguns campos podem ter conteúdo indevido para a construção do comando INSERT. Pode ser um SQL injection ou um simples caractere que rompe
        # a cadeia de caracteres que montam o comando de atualização no Banco. Podemos usar o PHP e fazer uma substituição de caracteres ou até mesmo
        # bloquear a execução dos comandos que seguem este trecho.
        #
        # Neste ponto do programa podemos usar funções do PHP para trocar caracteres indevidos para o INSERT.
        $_POST['dtcontratacao']=str_replace("'", "''", $_POST['dtcontratacao']);
        
        # Ajustando a tabela de simbolos recebidos/enviados para o BD para UTF8
        pg_query("SET NAMES 'utf8'");
        pg_query("SET CLIENT_ENCODING TO 'utf8'");
        pg_set_client_encoding('utf8');
        # exibindo mensagem de orientação
        printf("Incluindo o Registro...<br>\n");
        #--------------------------------------------------------------------------------------------------------------------------------------------
        # Executando o case que grava (INSERT) os dados na tabela seguros.
        # Tratamento da Transação
        # Inicio da transação - No PostgreSQL se inica com o comando BEGIN. Colocamos dentro de um WHILE para poder
        # controlar o reinicio da transação caso aconteça um DEADLOCK.
        $tentativa=TRUE;
        while ( $tentativa )
        { # 1.2.1.2.1-Laço de repetição para tratar a transação -------------------------------------------------------------------------------------
          $query = pg_send_query($link,"BEGIN");
          $result=pg_get_result($link);
          $erro=pg_result_error($result);
          # Depois que se inicia uma transação o comando enviado para o BD deve ser através da função pg_send_query().
          # Esta função avisa ao PostgreSQL que devem ser usados os LOGs de transação para acessar os dados.
          # A cada send_query o PostgreSQL responde com um sinal de status (erro ou não erro).
          # Por conta disso deve-se "ler" este status com as funções pg_getr_result() e pg_result_error().
          # Montando em uma variavel a data de cadastro no formato do BD
          # $dtcadmedico=$_POST['anocad'].'-'.$_POST['mescad'].'-'.$_POST['diacad'];
          # Vamos pegar o último código gravado na tabela seguros. Este trecho fica 'dentro' da transação para gerar
          # o bloqueio na página de dados que vai gravar o próximo registro.
          # Estamos gerando o valor da cp e NÃO usando campos autoincrementados PORQUE este recurso não está disponível em todos os SGBDs
          # e SE UM DIA um ilustre aluno trabalhar com um destes SGBD vai se lembrar que um professor ensinou a trabalhar a determinação
          # do próximo valor de uma chave primária DENTRO da aplicação. Para 'brincar' com o conceito...
          # SUPONDO que o bloco de incremento seja 7 (CINCO)... escrevemos.
          $proxcp=pg_result(pg_query("SELECT max(pkconta)+7 as CMAX FROM cadastro"),0,'CMAX');
          # A tabela pode estar vazia, neste caso o CMAX é nulo e $proxcp NÃO recebe valor. Então a proxima cp deve ser 1.
          $cp=( isset($proxcp) ) ? $proxcp : 7;
          # Montando o comando de INSERT (Dentro do laço de repatição das tentativas porque o valor da cp depende da leitura da tabela 'dentro' da transação)
          $cmd="INSERT INTO sitecriando VALUES ('$cp',
                                             
											'$_POST[txresenha]', 
                                            '$_POST[numero]',
                                            '$_POST[bairro]',
											'$_POST[rua]',
                                            '$_POST[senha]',                                                                                       
                                            '$_POST[txnomecli]',
                                            '$_POST[sexo]',                                            
                                            '$_POST[nomeusuario]') RETURNING pkconta";
          # O comando INSERT pode ser escrito em uma só linha (mais extenso), o que pode dificultar encontrar um erro eventual.
          # Na forma 'quebrada' fica mais fácil entender o comando.
          # Para o SGBD os sinais de enter e os espaços em branco não afeta o comando INSERT.
          # printf("$cmd<br>\n"); # Se quiser ver o comando na fase de teste, tire o comentário no início da linha.
          $comando=pg_send_query($link,$cmd);
          $result=pg_get_result($link);
          $erro=pg_result_error($result);
          $volta=pg_fetch_array($result);
          $cp=$volta['pkconta'];
          # O Próximo SWITCH trata as situações de erro. A função pg_get_result($link) retorna o número do erro do PostgreSQL.
          # Dentro deste SwitchCase atribui-se o valor de $mostra.
          # $mostra vale FALSE se acontecer algum erro na execução e TRUE se a transação terminar SEM erro.
          switch (TRUE)
          { # 1.2.1.1.3 - Avaliação da situação de erro (se existir).
            case $erro == "" :
            { # 1.2.1.1.3.1 - Nao tem erro! Concluir a transacao e Avisar o usuario. ----------------------------------------------------------------
              # Comando que foi EXECUTADO no BD podemos MOSTRAR o comando na tela para suporte ao usuário.
              #printf("$cmd<br>\n");
              #printf("$cp<br>\n");
              $query=pg_send_query($link,"COMMIT");
              printf("Registro <b>Inserido</b> com sucesso!<br>\n");
              $tentativa=FALSE;
              $mostra=TRUE;
              break;
            } # 1.2.1.1.3.1 -------------------------------------------------------------------------------------------------------------------------
            case $erro == "deadlock_detected" :
            { # 1.1.3.2 - Erro de DeadLock - Cancelar e Reiniciar a transacao -----------------------------------------------------------------------
              $query=pg_send_query($link,"ROLLBACK");
              $tentativa=TRUE;
              break;
            } # 1.2.1.1.3.2 -------------------------------------------------------------------------------------------------------------------------
            case $erro != '' AND  $erro!= 'deadlock_detected' :
            { # 1.2.1.1.3.3 - Erro! NÃO por deadlock. AVISAR o usuario. CANCELAR A transacao --------------------------------------------------------
              printf("<b>Erro na tentativa de Inserir!</b><br>\n");
              $mens=$result." : ".$erro;
              printf("Mensagem: $mens<br>\n");
              $query=pg_send_query($link,"ROLLBACK");
              $tentativa=FALSE;
              $mostra=FALSE;
              break;
            } # 1.2.1.1.3.3 -------------------------------------------------------------------------------------------------------------------------
          } # 1.2.1.1.3 - Fim do SWITCH tratando os status da transação -----------------------------------------------------------------------------
          $resultfinal=pg_get_result($link);
          $errofinal=pg_result_error($resultfinal);
        } # 1.2.1.2.1 - Fim do Laço de repetição para tratar a transação ----------------------------------------------------------------------------
        if ( $mostra )
        { # Executando a função do subprograma com o valor de $CP como cp. --------------------------------------------------------------------------
          showdata("$cp");
        } # -----------------------------------------------------------------------------------------------------------------------------------------
        # montando os botões do form com a função botoes e os parâmetros:
        # (Página,Menu,Saída,Reset,Ação,$salto) TRUE | FALSE para os 4 parâmetros esq-dir.
        botoes(FALSE,TRUE,TRUE,FALSE,NULL,$salto);
        printf("<br>\n");
        break;
      } # 1.2.1.3-Fim do Bloco de Tratamento da Transação -------------------------------------------------------------------------------------------
    } # 1.2.1-Fim do divisor de blocos principal ----------------------------------------------------------------------------------------------------
    break;
  }




printf("Nome: $_POST[nomeusuario]<br>\n");
printf("Senha: $_POST[senha]<br>\n");
printf("Nome Completo: $_POST[txnomecli]<br>\n");
printf("Rua: $_POST[rua]<br>\n");
printf("Bairro: $_POST[bairro]<br>\n");
printf("Numero: $_POST[numero]<br>\n");
printf("Complemento: $_POST[txresenha]<br>\n");
printf("Sexo: $_POST[sexo]<br>\n");
printf("</html>\n</body>\n");
}
?>