<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Prearray
	{
		
		function Prearray()
		{}
		
		public function estado()
		{
			$estados = array(
				'ac' => 'AC - Acre',
				'al' => 'AL - Alagoas',
				'ap' => 'AP - Amap&aacute;',
				'am' => 'AM - Amazonas',
				'ba' => 'BA - Bahia',
				'ce' => 'CE - Cear&aacute;',
				'df' => 'DF - Distrito Federal',
				'es' => 'ES - Esp&iacute;rito Santo',
				'go' => 'GO - Goi&aacute;is',
				'ma' => 'MA - Maranh&atilde;o',
				'mt' => 'MT - Mato Grosso',
				'ms' => 'MS - Mato Grosso do Sul',
				'mg' => 'MG - Minas Gerais',
				'pa' => 'PA - Par&aacute;',
				'pb' => 'PB - Para&iacute;ba',
				'pr' => 'PR - Paran&aacute;',
				'pe' => 'PE - Pernambuco',
				'pi' => 'PI - Piau&iacute;',
				'rj' => 'RJ - Rio de Janeiro',
				'rs' => 'RS - Rio Grande do Sul',
			    'rn' => 'RN - Rio Grande do Norte',
				'ro' => 'RO - Rond&ocirc;nia',
				'rr' => 'RR - Roraima',
				'sc' => 'SC - Santa Catarina',
				'sp' => 'SP - S&atilde;o Paulo',
				'se' => 'SE - Sergipe',
				'to' => 'TO - Tocantis',
			);
			
			return $estados;
		}
		
		public function sexo()
		{
			$sexo = array(
				'm' => 'Masculino',
				'f'	=> 'Feminino'
			);
			
			return $sexo;
		}
		
		public function ativo_desativo()
		{
			$status = array(
				'a' => 'Ativo',
				'd'	=> 'Desativo'				
			);
			
			return $status;
		}
		
		public function sim_nao()
		{
			$sim_nao = array(
				's' => 'Sim',
				'n' => 'N&atilde;o'
			);
			
			return $sim_nao;
		}
		
	}
