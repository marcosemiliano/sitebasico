<?php
printf("<html>
<head>");
    printf("<link rel='stylesheet' type='text/css' href='style.css'>");
printf("<title> Minha Primeira Home Page </title>");
printf("</head>");

 printf("<meta charset ='utf-8'>");
printf("<body bgcolor='white' text='black'>");
printf("<div class='container'>");
printf("<h1> MINHA PAGINA PESSOAL </h1>'");

  
  printf("<div>
  <div id='div_esquerda'>
  <ul> 
   <li><a href='./produtos.html' style='color:#DCDCDC;'>produtos</a><br><br><br>
     
  </li>
  <li><a href='./cadastrar.html' style='color:#DCDCDC;'>cadastrar</a><br><br><br>
     
  </li>
  <li><a href='./compra style='color:#DCDCDC;'>compra</a><br><br><br>
    
  </li>
  
  </ul>
  </div>");
  printf("<div id='div_direita'>
    <form action='./pega.php' method='POST'>
<table>
<tr><td>Nome de usuario :</td><td><input type='text' name='nomeusuario' size='50' maxlength='120'><br></td></tr>
<tr><td>senha :</td><td><input type='text' name='senha' size='50' maxlength='120'><br></td></tr>
<tr><td>Nome completo :</td><td><input type='text' name='txnomecli' size='50' maxlength='120'><br></td></tr>
<tr><td>Rua :</td><td><input type='text' name='rua' size='100' maxlength='200'><br></td></tr>
<tr><td>Bairro :</td><td><input type='text' name='bairro' size='100' maxlength='200'><br></td></tr>
<tr><td>Numero :</td><td><input type='text' name='numero' size='10' maxlength='200'><br></td></tr>
	 <tr><td>Complemento :</td><td><textarea name='txresenha' rows='5' cols='50'  maxlength='500'></textarea><br></td></tr>
	 <tr><td>sexo :</td><td><select name='sexo'>
   <option value='Masculino'> masculino </option>
   <option value='Feminino'> feminino </option>
</select><br></td></tr>
	 

<br></td></tr>
	 <tr><td></td><td><input type='reset' value='Limpar'> <input type='submit' value='Enviar'></td></tr>
  
  </table>

</form>

  
  </div>
  </div>
 ");

printf(" 
<br><br>
Seja Bem Vindo,<br>

</div>
<footer>DESENVOLVIDO POR MARCOS EMILIANO DE LUCA GONÇALVES</footer>
</body>
</html>
");
?>