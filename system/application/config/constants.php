<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('URL_QUEM_SOMOS', 'quem_somos');
define('URL_COLUNAS_E_NOVIDADES', 'colunas_novidades/lista/');
define('URL_COLUNAS_E_NOVIDADES_DETALHE', 'colunas_novidades/detalhe/');
define('URL_PARCEIROS', 'parceiros/');
define('URL_NA_MIDIA', 'na_midia/');
define('URL_COMO_FUNCIONA', 'como_funciona/');
define('URL_PRODUTOS', 'produtos/listar/');
define('URL_AJAX_PRODUTOS', 'produtos/ajax_listar/');
define('URL_PRODUTOS_DETALHE', 'produtos/detalhe/');
define('URL_PONTOS_DE_TROCAS', 'pontos_de_trocas/');
define('URL_CONTATO', 'contato/');
define('URL_FAQ', 'faq/');
define('URL_USUARIO_LOGIN', 'usuario/login/');
define('URL_USUARIO_RECUPERAR_SENHA', 'usuario/recuperar_senha/');
define('URL_USUARIO_RECUPERAR_SENHA_CODIGO', 'usuario/recuperar/');
define('URL_USUARIO_LOGOUT', 'usuario/logout/');
define('URL_USUARIO_CRIAR_CONTA', 'usuario/criar_conta/');
define('URL_USUARIO_MEUS_DADOS', 'usuario/meus_dados/');
define('URL_USUARIO_ACEITAR_TROCA', 'usuario/aceitar_troca/');
define('URL_USUARIO_RECUSAR_TROCA', 'usuario/recusar_troca/');
define('URL_USUARIO_FINALIZAR_TROCA', 'usuario/finalizar_troca/');
define('URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO', 'usuario/meus_dados/2/');
define('URL_USUARIO_MEUS_DADOS_MEUS_BRINQUEDOS', 'usuario/meus_dados/1/');
define('URL_USUARIO_MEUS_DADOS_MINHAS_TROCAS', 'usuario/meus_dados/3/');
define('URL_USUARIO_MEUS_DADOS_MENSAGENS', 'usuario/meus_dados/4/');
define('URL_USUARIO_EDITAR_BRINQUEDO', 'usuario/editar_brinquedo/');
define('URL_USUARIO_DELETAR_BRINQUEDO', 'usuario/deletar_brinquedo/');
define('URL_USUARIO_IMAGENS_BRINQUEDO', 'usuario/imagens/');
define('URL_USUARIO_DELETAR_IMAGEM', 'usuario/deletar_imagem/');

define('URL_UPLOAD_IMAGE', 'uploads/image/');
