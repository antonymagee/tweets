<?php
	
	$unqNum = GetHash(9);
	echo $unqNum;

	function GetHash($qtd){
        //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
        $Caracteres = 'abcdefghijklmnopqrstuvqxyz1234567890';
        $QuantidadeCaracteres = strlen($Caracteres);
        $QuantidadeCaracteres--;
    
        $Hash=NULL;
            for($x=1;$x<=$qtd;$x++){
                $Posicao = rand(0,$QuantidadeCaracteres);
                $Hash .= substr($Caracteres,$Posicao,1);
            }
    
        return $Hash;
        }

?>