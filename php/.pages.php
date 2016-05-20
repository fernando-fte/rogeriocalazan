<?php
	# # # # # # # # # /  DEFINE PAGINAS DA APLICAÇÃO / # # # # # # # # #
	// Syntax = '@query' => array( '@group' => array( 1 => 2 ), '@double' => true '@next' => array( 'id', 'sku'))
	$settings['pages'] = array(

		// Aparametros padrão que todos os elemento herdarão
		'@default' => array(

			'@head' => array(
				'@meta' => array(
					'@config' => array(
						array('charset'=>'utf-8'),
						array('http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge'),
						array('name'=>'viewport', 'content'=>'width=device-width, initial-scale=1')
					),
					'description' => null,
					'keywords' => null,
					'author' => 'FTE Developer by VG Consultoria'
				),

				'@title' => 'Home',

				'@style' => array(
					'bootstrap-css' => 'css',
					'fontawesome' => 'css',
					'style-less'  => 'less'
				),
			),

			'@body_end' => array(

				'@script' => array( 
					'jquery' => 'script',
					'bootstrap-js' => 'script',
					'coffee' => 'script',
					'less' => 'script',
					'app-coffee' => 'script-coffee'
				)
			),

			'@include' => array(
				$settings['dir']['app'].'/menu.html'
			)
		),

		'home' => array(
			'@head' => array(
				'@title' => 'Bem vindo',
			),
			'@include' => array(
				$settings['dir']['app'].'/home.banner.html',
				$settings['dir']['app'].'/home.produtos.html',
				$settings['dir']['app'].'/rodape.html',
				$settings['dir']['app'].'/modal.pagamento.html'
			)
		),

		'produto' => array(

			'@head' => array(
				'@title' => 'Bem vindo',
			),

			'@include' => array(
				$settings['dir']['app'].'/produto.html',
				$settings['dir']['app'].'/rodape.html',
				$settings['dir']['app'].'/modal.pagamento.html'
			)
		)
	)
	# # # # # # # # # /  DEFINE PAGINAS DA APLICAÇÃO / # # # # # # # # #
?>
