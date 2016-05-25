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
					// 'bootstrap-css' => 'css',
					'fontawesome' => 'css',
					'style-less'  => 'less'
					// 'style-css'  => 'css'
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
				$settings['dir']['app-basic'].'/menu.html'
			)
		),

		'home' => array(
			'@head' => array(
				'@title' => 'Bem vindo',
			),
			'@include' => array(
				$settings['dir']['app-header'].'/home.html',
				$settings['dir']['app-block'].'/banner.html',
				$settings['dir']['app-block'].'/produto.list.html',
				$settings['dir']['app-basic'].'/rodape.html',
				$settings['dir']['app-block'].'/modal.pagamento.html'
			)
		),

		'curso' => array(

			'@head' => array(
				'@title' => 'Produto',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/produto.html',
				$settings['dir']['app-block'].'/produto.item.html',
				$settings['dir']['app-block'].'/produto.list.html',
				$settings['dir']['app-basic'].'/rodape.html',
				$settings['dir']['app-block'].'/modal.pagamento.html'
			)
		),

		'promocao' => array(

			'@head' => array(
				'@title' => 'Promocao',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/promocao.html',
				$settings['dir']['app-block'].'/produto.list.html',
				$settings['dir']['app-basic'].'/rodape.html',
			)
		),

		'todos' => array(

			'@head' => array(
				'@title' => 'Todos produtos',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/todos.html',
				$settings['dir']['app-block'].'/produto.list.html',
				$settings['dir']['app-basic'].'/rodape.html',
			)
		),

		'categoria' => array(

			'@head' => array(
				'@title' => 'Nome da Categoria',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/categoria.html',
				$settings['dir']['app-block'].'/produto.list.html',
				$settings['dir']['app-basic'].'/rodape.html',
			)
		),

		'carrinho' => array(

			'@head' => array(
				'@title' => 'Finalizar compra',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/carrinho.html',
				$settings['dir']['app-block'].'/carrinho.produtos.html',
				$settings['dir']['app-basic'].'/rodape.html',
				$settings['dir']['app-block'].'/modal.pagamento.html'
			)
		),

		'account' => array(

			'@head' => array(
				'@title' => 'Finalizar compra',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/user.html',
				$settings['dir']['app-user'].'/menu.html',
				$settings['dir']['app-user'].'/visao-geral.html',
				$settings['dir']['app-basic'].'/rodape.html',
			),

			'edt' => array(
				'@no_default' => array('@include'),

				'perfil' => array(
					'@head' => array(
						'@title' => 'Editar perfil',
					),
					'@include' => array(
						$settings['dir']['app-basic'].'/menu.html',
						$settings['dir']['app-header'].'/user.html',
						$settings['dir']['app-user'].'/menu.html',
						$settings['dir']['app-user'].'/edt-perfil.html',
						$settings['dir']['app-basic'].'/rodape.html',
					)
				),

				'senha' => array(
					'@head' => array(
						'@title' => 'Editar senha',
					),
					'@include' => array(
						$settings['dir']['app-basic'].'/menu.html',
						$settings['dir']['app-header'].'/user.html',
						$settings['dir']['app-user'].'/menu.html',
						$settings['dir']['app-user'].'/edt-senha.html',
						$settings['dir']['app-basic'].'/rodape.html',
					)
				),
			),

			'cursos' => array(
				'@no_default' => array('@include'),
				'@include' => array(
					$settings['dir']['app-basic'].'/menu.html',
					$settings['dir']['app-header'].'/user.html',
					$settings['dir']['app-user'].'/menu.html',
					$settings['dir']['app-user'].'/compras.html',
					$settings['dir']['app-basic'].'/rodape.html',
				)
			)
		),

		'contato' => array(

			'@head' => array(
				'@title' => 'Contato',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/contato.html',
				$settings['dir']['app-basic'].'/rodape.html',
			)
		),

		'sobre' => array(

			'@head' => array(
				'@title' => 'Sobre nós',
			),

			'@include' => array(
				$settings['dir']['app-header'].'/sobre.html',
				$settings['dir']['app-basic'].'/rodape.html',
			)
		)
	)
	# # # # # # # # # /  DEFINE PAGINAS DA APLICAÇÃO / # # # # # # # # #
?>
