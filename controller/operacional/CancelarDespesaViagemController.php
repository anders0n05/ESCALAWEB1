<?php
include_once('../tecnologia/Sistema.php');

$usuario = $_SESSION['usuario'];
$senha = $_SESSION['senha'];
$senhaNaoCriptografada = $_SESSION['senhaNaoCriptografada'];
$despesaHandle = null;
$despesaHandle = Sistema::getGet('despesaHandle');
$motivo = Sistema::getGet('motivo');
$ref = Sistema::getGet('ref');

$mensagem = null;
$protocolo = null;
$sucesso = null;

try {
	
    $params = array("viagemDespesa" => $despesaHandle,
					"motivo" => $motivo
    );
	$webservice = 'Operacional';
   include_once('../tecnologia/WebService.php');
   
   if($WebServiceOffline){
		$_SESSION['mensagem'] = 'Erro ao conectar com o WebService, tente novamente mais tarde';
		header('Location: ../../view/operacional/'.$ref.'.php?despesa='.$despesaHandle.'');
		exit;
	}

    $result = $clientSoap->__soapCall("CancelarViagemDespesa", array("CancelarViagemDespesa" => array("viagemDespesa" => $params)));
     
	$retorno = $result->CancelarViagemDespesaResult;
	
	if(!empty($retorno->mensagem)){
		$mensagem = $retorno->mensagem; 
	}
	if(!empty($retorno->protocolo)){
		$protocolo = $retorno->protocolo;
	}
	if(!empty($retorno->sucesso)){
		$sucesso = $retorno->sucesso; 
	}
	
	if($mensagem == null and $protocolo == null and $sucesso == null){
	$_SESSION['mensagem'] = 'Erro ao conectar com o WebService, tente novamente mais tarde';
		
		$_SESSION['tipo'] = $tipo;
		$_SESSION['tipoHandle'] = $tipoHandle;
		$_SESSION['viagem'] = $viagem;
		$_SESSION['viagemHandle'] = $viagemHandle;
		$_SESSION['data'] = $data;
		$_SESSION['hora'] = $hora;
		$_SESSION['quantidade'] = $quantidade;
		$_SESSION['ValorUnitario'] = $ValorUnitario;
		$_SESSION['mensaValorTotalgem'] = $ValorTotal;
		$_SESSION['despesa'] = $despesa;
		$_SESSION['despesaHandle'] = $despesaHandle;
		$_SESSION['fornecedor'] = $fornecedor;
		$_SESSION['fornecedorHandle'] = $fornecedorHandle;
		$_SESSION['FormaPagamento'] = $FormaPagamento;
		$_SESSION['FormaPagamentoHandle'] = $FormaPagamentoHandle;
		$_SESSION['CondicaoPagamento'] = $CondicaoPagamento;
		$_SESSION['CondicaoPagamentoHandle'] = $CondicaoPagamentoHandle;
		$_SESSION['observacao'] = $observacao;
		echo"<script language='javascript' type='text/javascript'>alert('$despesaHandle')</script>";

	header('Location: ../../view/operacional/'.$ref.'.php?despesa='.$despesaHandle);	
	}
	
	
	if($sucesso == 'True'){
		$_SESSION['protocolo'] = $protocolo;
		
		header('Location: ../../view/operacional/'.$ref.'.php?despesa='.$despesaHandle.'');
		
	}
	else if($sucesso == 'False'){
		$_SESSION['mensagem'] = $mensagem;

		header('Location: ../../view/operacional/'.$ref.'.php?despesa='.$despesaHandle.'');
	}
	
} 
catch (SoapFault $e) {
    var_dump($e->getMessage());
		
	$_SESSION['mensagem'] = 'Erro ao conectar com o WebService, tente novamente mais tarde';
	header('Location: ../../view/operacional/'.$ref.'.php?despesa='.$despesaHandle.'');
}
?>