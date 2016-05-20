<?php 

	# # # \ TRATA CAMIMHOS RELATIVOS \ # # #
	function path_relative($post) {
		# $post = $settings['file'] | $settings['dir'] type text

		# # # DECLARA INSTANCIAS DO RESULTADO
		$result = array(
			'success' => null,
			'erro' => null,
			'this' => 'F::path_relative',
			'done' => null,
			'process' => array (
				'path relativo' => array ('success' => true),
				'path no post' => array ('success' => null)
			),
		);

		# # # # # // # # # # # #
		# remove argumentos
		$me = explode('?', $_SERVER['REQUEST_URI'])[0];

		# remove nó root da pasta do projeto
		$me = str_replace($GLOBALS['settings']['wwwproj'], '', $me);

		# explode as barras
		$me = explode('/', $me);

		// print_r($me);
		# # valida se foi adionado no fim da url uma barra
		if ($me[(count($me) - 1)] != '') {

			# valida se é um arquivo
			unset($me[(count($me) - 1)]);
		}

		# #inicia loop para seelecionar o path relativo
		for ($i=0; $i < count($me); $i++) { 

			// print $me[$i]."\n";
			if ($me[$i] != '') {

				$result['done'] .= '../';
			}
		}

		# apaga me
		unset($me);
		# # # # # // # # # # # #

		# # # # # // # # # # # #
		if ($post != false) {
			# reserva resultado do processo de separar o path relativo
			$result['process']['path relativo']['log'] = $result['done'];


			# remove o caminho root do item
			$result['done'] = str_replace($GLOBALS['settings']['wwwroot'].'/', $result['done'], $post);

			# remove caminhos duplos "//"
			$result['done'] = str_replace('//', '/', $result['done']);

			# declara processo como valido
			$result['process']['path no post']['success'] = true;
		}

		# apaga me
		unset($me);
		# # # # # // # # # # # #

		return $result;
	}

	# # # \ HTMLS BASICOS \ # # #
	function html_required($post) {
		// Recebe tipo de tag

		switch ($post['type']) {

			case 'title':
				$result = '<title>'.$post['content'].'</title>';
				break;

			case 'meta':
				$result = '<meta '.$post['content'].'>';
				break;

			case 'css':
				$result = '<link rel="stylesheet" type="text/css" href="'.path_relative($GLOBALS['settings']['file'][$post['content']])['done'].'">';
				break;

			case 'less':
				$result = '<link rel="stylesheet/less" type="text/css" href="'.path_relative($GLOBALS['settings']['file'][$post['content']])['done'].'">';
				break;

			case 'script':
				$result = '<script src="'.path_relative($GLOBALS['settings']['file'][$post['content']])['done'].'"></script>';
				break;

			case 'script-js':
				$result = '<script type="text/javascript" src="'.path_relative($GLOBALS['settings']['file'][$post['content']])['done'].'"></script>';
				break;

			case 'script-coffee':
				$result = '<script type="text/coffeescript" src="'.path_relative($GLOBALS['settings']['file'][$post['content']])['done'].'"></script>';
				break;
		}

		return $result;
	}

	# # # \ CONTRUÇÃO DO HTML \ # # #
	function construct_page_required($page, $content, $func) {

		# DECLARA INSTANCIAS DO RESULTADO
		$result = array(
			'success' => null,
			'erro' => null,
			'done' => null,
			'this' => 'F::construct_page_required',
			'process' => array (
				'valida-page' => false,
				'@help' => false,
				'inicia' => array(
					'success' => null,
					'log' => array(
						'@page' => null,
						'@default' => null
					)
				),
				'no_default' => array('success' => null),
				'inicia content' => false,
				'valida content' => array('success' => true)
			)
		);

		# DECLARA INSTANCIAS DE RESERVA
		$reserve = array(
			'@default' => null,
			'@pages' => null
		);


		# # # # # / VALIDAÇÃO / # # # # # #
		# Valida se esta sendo recebido parametros da função
		if ($func == true && gettype($func) == 'array') {

			# # modifica nome da função para identificar como sub-item
			$result['this'] = 'me';

			# # reserva os dados enviador pela função
			$reserve = $func;
		}

		# Valida se foi recebido a pagina
		if ($page == true) {

			# # valida se foi recebido uma lista direta
			if (gettype($page) == 'string') {

				# # # explode e separa as paginas
				$page = explode('->', $page);

				# # # define o processo de tratamento
				$result['process']['trata-page-direct'] = true;
			}

			# # Valida os parametros sao uma array
			if (gettype($page) == 'array') {

				# # # valida se é uma sub chamada
				if ($result['this'] == 'me') {

					# # # valida se a pagina existe
					if (array_key_exists($page[0], $reserve['@pages'])) {

						# # # # valida se a pagina existe
						$result['process']['valida-page'] = true;
					}
				}

				# # # caso o parametro nao seja uma sub chamada
				if ($result['this'] == 'F::construct_page_required') {

					# # # valida se a pagina existe
					if (array_key_exists($page[0], $GLOBALS['settings']['pages'])) {

						# # # # valida se a pagina existe
						$result['process']['valida-page'] = true;
					}
				}
			}

			# # Monta erro caso a pagina nao seja valida
			if ($result['process']['valida-page'] == false) {

				$result['erro'] = true;
				$result['done'] = 'A pagina "'.$page[0].'" não esta listada nas configurações';
			}
		}

		# # Valida se é um pedido de help
		if ($page == false) {

			# # # configura help
			$result['process']['@help'] = true;
		}
		# # # # # / VALIDAÇÃO / # # # # # #



		# # # # # / TRATA HELP / # # # # # #
		# Declara o help para a chamada a da função
		if ($result['process']['@help'] == true) {

			# # Decalra em done o help
			$result['done'] = 'Esta sendo esperado ao menos um $page que pode ser uma lista array ou uma string com os valores separados por "->" assim "pagina->pagina2"';
		}
		# # # # # / TRATA HELP / # # # # # #


		# # / INICIA CASO O PROCESSO DE VALIDAÇÃO DE PAGINA ESTEJA CORRETO / # #
		if ($result['process']['valida-page'] == true) {

			# # # # # / RESERVA OS DADOS DAS PAGINAS / # # # # # #
			if ($result['this'] == 'F::construct_page_required') {
				$reserve['@pages'] = $GLOBALS['settings']['pages'][$page[0]];
				$reserve['@default'] = $GLOBALS['settings']['pages']['@default'];
			}
			if ($result['this'] == 'me') {
				$reserve['@pages'] = $reserve['@pages'][$page[0]];
				$reserve['@default'] = $reserve['@default'];
			}
			# # # # # / RESERVA OS DADOS DAS PAGINAS / # # # # # #

			# # # #
			# reserva pagina na posição atual
			$me = $reserve['@pages'];
			# # # #

			# # # # # / MONTA DEFAULT / # # # # # #
			# # / TRATA REMOVE DEFAULT / # #
			if (array_key_exists('@no_default', $me)) {

				# # # Adiciona processo de remoção de default
				$result['process']['no_default']['success'] = true;


				# # / TRATA REMOVE DEFAULT / # #
				# # # Valida se é preciso rezetar todos os dados
				if (gettype($me['@no_default']) == 'boolean' && $me['@no_default'] == true) {

					# # # # Apaga os dados de default
					unset($reserve['@default']);

					# # # # Acrecenta um alerta
					$result['process']['no_default']['warning'][] = 'Foi resetado os dados de default da página "'.$page[0].'"';
				}

				# # # Valida se o default é parcial
				if (gettype($me['@no_default']) == 'array') {

					# # # Adiciona processo de remoção de default
					$result['process']['no_default']['success'] = true;

					# # # # Seleciona todos os itens
					for ($i=0; $i < count($me['@no_default']); $i++) { 
						
						# # # # # Trata cada instancia
						switch ($me['@no_default'][$i]) {

							case '@head': unset($reserve['@default']['@head']); break;
							case '@head->title': unset($reserve['@default']['@head']['@title']); break;
							case '@head->meta': unset($reserve['@default']['@head']['@meta']); break;
							case '@head->style': unset($reserve['@default']['@head']['@style']); break;
							case '@head->script': unset($reserve['@default']['@head']['@script']); break;

							case '@body_end': unset($reserve['@default']['@body_end']); break;
							case '@body_end->style': unset($reserve['@default']['@body_end']['@style']); break;
							case '@body_end->script': unset($reserve['@default']['@body_end']['@script']); break;

							case '@include': unset($reserve['@default']['@include']); break;
						}

						# # # # Acrecenta um alerta
						$result['process']['no_default']['warning'][] = 'Foi resetado o default "'.$me['@no_default'][$i].'" da página "'.$page[0].'"';
					}
					unset($i);
				}
			}
			# # / TRATA REMOVE DEFAULT / # #


			# # / VALIDA SE EXISTE DEFAULT / # #
			if (array_key_exists('@default', $me)) {
				
				# # # Adiciona processo de remoção de default
				$result['process']['default']['success'] = true;

				# # # Mescla com o default reservado
				$reserve['@default'] = array_replace_recursive($reserve['@default'], $me['@default']);
			}
			# # / VALIDA SE EXISTE DEFAULT / # #
			# # # # # / MONTA DEFAULT / # # # # # #


			# # # # # / TRATA SE DEVE SER SELECIONADO UMA SUB PASTA / # # # # # #
			# # Solicita sub-valor
			if (count($page) > 1) {

				# Decalra processo de sub pastas
				$result['process']['sub-pages']['success'] = true;


				$temp['func']['@default'] = $reserve['@default'];
				$temp['func']['@pages'] = $me;
				$temp['page'] = $page;
				array_shift($temp['page']);

				$result['process']['sub-pages']['log'] = construct_page_required($temp['page'], null,$temp['func']);

				# Caso o sucesso no processo
				if ($result['process']['sub-pages']['log']['success'] == true) {

					# define os valores
					$reserve = $result['process']['sub-pages']['log']['done'];
				}
				unset($temp);
			}
			# # # # # / TRATA SE DEVE SER SELECIONADO UMA SUB PASTA / # # # # # #


			# # # # # # / VALIDA SE É UMA SOLICITAÇÃO DA FUNÇÃO / # # # # # #
			if ($result['this'] == 'me') {

				$result['done']['@default'] = $reserve['@default'];
				$result['done']['@pages'] = $reserve['@pages'];
				$result['success'] = true;
			}
			# # # # # # / VALIDA SE É UMA SOLICITAÇÃO DA FUNÇÃO / # # # # # #


			# # # # # # / DEFINE O INICIO DO TRATAMENTO DOS ELEMENTOS PARA ESSA PAGINA / # # # # # #
			if ($result['this'] == 'F::construct_page_required') {

				# # Valida o inicio dos tratamentos
				$result['process']['inicia']['success'] = true;

				# # Mescla parametros da pagina con default
				# # # Cria lista pra validação
				$temp['list'] = array('@head', '@body_end', '@include');

				# # # Inicia loop para selecionar cada tipo de item
				for ($i=0; $i < count($temp['list']); $i++) { 

					# # # # Valida se existe as condições na pagina
					if (array_key_exists($temp['list'][$i], $reserve['@pages'])) {

						# # # # # Adiciona o elemento em default se nao existe
						if (!array_key_exists($temp['list'][$i], $reserve['@default'])) {
							$reserve['@default'][$temp['list'][$i]] = array();
						}

						# # # # # Subistitui o reserve->default pelos parametros da pagina
						$reserve['@default'][$temp['list'][$i]] = ($temp['list'][$i] == '@include' ? array_merge_recursive($reserve['@default'][$temp['list'][$i]], $reserve['@pages'][$temp['list'][$i]]) : array_replace_recursive($reserve['@default'][$temp['list'][$i]], $reserve['@pages'][$temp['list'][$i]]));
					}
				}
				unset($temp, $i); // Paga os valores utilizados
			}
			# # # # # # / DEFINE O INICIO DO TRATAMENTO DOS ELEMENTOS PARA ESSA PAGINA / # # # # # #
		}

		unset($me); // Apaga os parametros usados temposariamente
		# # / INICIA CASO O PROCESSO DE VALIDAÇÃO DE PAGINA ESTEJA CORRETO / # #


		# # / INICIA OS TRATAMENTOS DOS ELEMENTOS/ # #
		if ($result['process']['inicia']['success'] == true) {


			# # Valida se é recebido um content
			if ($content == true) {

				# # Modifica as condições recebidas caso contrario usa o include
				switch ($content) {
					case 'head': $content = '@head'; break;
					case 'body_end': $content = '@body_end'; break;
					case 'include': $content = '@include'; break;
				}

				# # Valida se esta sendo recebido contents
				if ($content != '') { $result['process']['inicia content'] = true;}
				# # # caso content seja vazio preenche como "include"
				else { $content = '@include'; $result['process']['inicia content'] = true; $result['warning']['inicia content'] = 'Foi adicionado automaticamente o valor de "include"'; }

				# seleciona apenas os itens a setem tratados
				$me = $reserve['@default'][$content];

				# Monta estrutura para HEAD
				if ($content == '@head') {

					# # Inicia head
					$temp['construct'] .= "\n\t<head>";

					# # # Monta cada meta-tag
					if (array_key_exists('@meta', $me)) {
						
						# # # # Seleciona cada meta-tag
						foreach ($me['@meta'] as $key => $val) {

							# # # # # Valida se é um conjunto de metas do tipo configruação
							if ($key == '@config') {

								# # # # # # Seleciona cada item dentro de config
								for ($i=0; $i < count($val); $i++) { 

									# # # # # # Separa temp->meta para reservar o conjunto de atributos
									$temp['meta'] = '';

									# # # # # # Explode cada item na posição atual
									foreach ($val[$i] as $key2 => $val2) {
										$temp['meta'] .= '"'.$key2.'"="'.$val2.'" ';
									}

									# # # # # # adiona o a meta tag de configruação
									$temp['construct'] .= "\n\t\t".html_required(array('type'=>'meta', 'content'=>$temp['meta']));

									unset($key2, $val2);
								}

								unset($i,$temp['meta']);
							}

							# # # # # valida se as meta são de indexação
							else {

								$temp['construct'] .= "\n\t\t".html_required(array('type'=>'meta', 'content'=>'"name"="'.$key.'" "content"="'.$val.'"'));
							}
						}

						unset($key, $val);
					}
					# # # //

					# # # Adiciona title
					$temp['construct'] .= "\n\t\t".html_required(array('type'=>'title', 'content'=>$me['@title']));
					# # # //

					# # # Monta css
					if (array_key_exists('@style', $me)) {
						foreach ($me['@style'] as $key => $val) {

							$temp['construct'] .= "\n\t\t".html_required(array('type'=>$val, 'content'=>$key));
						}

						unset($key, $val);
					}
					# # # //

					# # # Monta script
					if (array_key_exists('@script', $me)) {
						foreach ($me['@script'] as $key => $val) {

							$temp['construct'] .= "\n\t\t".html_required(array('type'=>$val, 'content'=>$key));
						}

						unset($key, $val);
					}
					# # # //

					# # Fecha head
					$temp['construct'] .= "\n\t</head>";
					$temp['construct'] .= "\n";

					# reserva os dados tratados em done
					$result['done'] = $temp['construct'];

					# imprime done
					echo $result['done'];

					# apaga temp
					unset($temp);
				}

				# Monta estrutura para BODY_END
				if ($content == '@body_end') {

					# Inicia 
					$temp['construct'] .= "\n";

					# # # Monta script
					if (array_key_exists('@script', $me)) {

						foreach ($me['@script'] as $key => $val) {

							$temp['construct'] .= "\n\t\t".html_required(array('type'=>$val, 'content'=>$key));
						}

						unset($key, $val);
					}
					# # # //

					# Fecha
					$temp['construct'] .= "\n";

					# reserva os dados tratados em done
					$result['done'] = $temp['construct'];

					# imprime done
					echo $result['done'];

					# apaga temp
					unset($temp);
				}

				# Monta estrutura para INCLUDE
				if ($content == '@include') {

					# # # Valida se existe includes
					if (count($me) > 0) {

						# # # Navega em cada valor a ser incluido
						for ($i=0; $i < count($me); $i++) { 

							# # # inclui cada item da biblioteca
							$temp = include path_relative($me[$i])['done'];

							# # # caso nao tenha sido incluido tenta novamente
							if ($temp == false) {
								include str_replace('..', '.', path_relative($me[$i])['done']);
							}

							$result['process']['include']['log'][$i] = path_relative($me[$i])['done'];
						}
						unset($temp);
						$result['success'] = true;
					}

					# # # Caso não exista includes
					else {
						$result['success'] = false;
						$result['process']['include']['success'] = false;
						$result['process']['include']['erro'] = 'Não foi descrito nem uma lista de inclusão em "'.$page[0].'"';
						$result['erro'] = $result['process']['include']['erro'];
					}

					unset($me);
				}
			}
		}
		# # / INICIA OS TRATAMENTOS DOS ELEMENTOS/ # #

		# Retorna os dados da função
		return $result;
	}

	# # # \ CONFIGURA PAGINAS \ # # #
	function parse_navgation($func) {

		# # # DECLARA INSTANCIAS DO RESULTADO
		$result = array(
			'success' => null,
			'erro' => null,
			'this' => 'F::monta_navegacao',
			'done' => null,
			'process' => array (
				'loop-paginas' => array(
					'success' => null
				),
			),
		);

		# # # DECLARA INSTANCIAS RESERVADAS
		$reserve = array(
			'map' => null,
			'position' => null,
			'nav' => null
		);


		# # # \ VALIDA SE É UMA SUB-SOLICITAÇÃO \ # # #
		if ($func != false) { $result['this'] = 'me'; }
		# # # \ VALIDA SE É UMA SUB-SOLICITAÇÃO \ # # #


		# # # \ RESERVA OS VALORES \ # # #
		# caso não seja uma suv função
		if ($result['this'] != 'me') {
			$reserve['map'] =  explode('/', explode('?', str_replace($GLOBALS['settings']['wwwproj'], '', $_SERVER['REQUEST_URI']))[0]);
			$reserve['position'] = 0;
			$reserve['nav'] = $GLOBALS['settings']['pages'];

			# # # # \ Apaga todos os valores nulos \ # # # #
			$me = $reserve['map'];
			$reserve['map'] = array();
			for ($i=0; $i < count($me); $i++) { 

				# # # Seleciona apenas o valor valido
				if ($me[$i] != '') {
					array_push($reserve['map'], $me[$i]);
				}
			}
			unset($i, $me);
			# # # # \ Apaga todos os valores nulos \ # # # #
		}
		# caso seja uma suv função
		if ($result['this'] == 'me') { $reserve =  $func; }
		# # # \ RESERVA OS VALORES \ # # #

		# # # \ INICIA NAVEGAÇÃO EM LOOP \ # # #
		if (count($reserve['map']) > 0) {

			# Inclui em $me o valor reservado
			$me = $reserve['map'];

			# # Seleciona o primeiro item da posição valido
			$i = $reserve['position'];

			# # # Guarda valor da proxima posição
			$reserve['position'] = ($i + 1);

			# # # Valida se existe a pagina
			if (array_key_exists($me[$i], $reserve['nav'])) {

				# # # # Valida se existe mais argumentos
				if (count($me) > ($i + 1) ) {

					# # # # # re envia os dados para as sub funções
					$reserve = parse_navgation(array('map' => $me, 'position' => $reserve['position'], 'nav' => $reserve['nav'][$me[$i]]))['done'];
				}

				# # # # Valida se é o ultimo argumento
				if (count($me) == ($i + 1) ) {
					$reserve['position']++;
				}
			}

			unset($i, $me);


			# # # Retorna dados caso a função seja sub-função
			if ($result['this'] == 	'me') {
				
				# # # Reserva como pronto os dados tratados
				$result['done'] = $reserve;
			}

			# # # Trata os dados
			if ($result['this'] != 	'me') {

				# # Reserva dados a serem tratados
				$me = $reserve['map'];

				# # Diminui 1 posição
				$reserve['position']--;

				# # declara valores
				$result['done'] = array('path' => array(), 'query' => array());

				# # Trata cada item
				for ($i=0; $i < count($me); $i++) { 

					# # # Caso seja um caminho de pastas
					if ($i < $reserve['position']) {
						array_push($result['done']['path'], $me[$i]);
					}

					# # # Caso seja um comanda GET
					if ($i >= $reserve['position']) {
						array_push($result['done']['query'], $me[$i]);

					}
				}
				unset($me, $i);

				# # Valida se existe algum parametro e retorna falso caso nao tenha
				$result['done']['path'] = (count($result['done']['path']) == 0 ? false : $result['done']['path']);
				$result['done']['query'] = (count($result['done']['query']) == 0 ? false : $result['done']['query']);

				# # Trata query
				if ($result['done']['query'] != false) {

					# # # Valida se a pagina trata query
					if (array_key_exists('@query', $reserve['nav'])) {
						$me = $reserve['nav']['@query'];
						$temp = array();

						# valida se é do tipo group
						if (array_key_exists('@group', $me)) {

							for ($i=0; $i < count(array_keys($me['@group'])); $i++) { 
								$k = array_keys($me['@group'])[$i];
								$v = array_values($me['@group'])[$i];

								# # MOnsta chaves
								$temp[$result['done']['query'][$k]] = $result['done']['query'][$v];

								# # remove chaves
								unset($result['done']['query'][$k], $result['done']['query'][$v]);
							}
							unset($i, $k, $v);

						}

						if (array_key_exists('@double', $me)) {
							for ($i=0; $i < count($result['done']['query']); $i++) { 
								# reserva double
								$temp[$result['done']['query'][$i]] = $result['done']['query'][($i + 1)];

								# # remove chaves
								unset($result['done']['query'][$k], $result['done']['query'][$v]);

								# adiona de dois em dois
								$i = $i + 2;
							}
							unset($i);
						}

						if (array_key_exists('@next', $me)) {

							# Inverte cada instancia
							$b = array_flip($result['done']['query']);
							for ($i=0; $i < count($me['@next']); $i++) { 

								# reserva valor especifico
								$temp[$me['@next'][$i]] = $result['done']['query'][($b[$me['@next'][$i]] + 1)];

								unset($result['done']['query'][($b[$me['@next'][$i]] + 1)], $result['done']['query'][$b[$me['@next'][$i]]]);
							}
							unset($a, $b, $i);
						}


						# # # Finaliza montagem
						$result['done']['query'] = array_values($result['done']['query']);
						for ($i=0; $i < count($result['done']['query']); $i++) { 
							
							$temp[$result['done']['query'][$i]] = '';
						}
	
						$result['done']['query'] = $temp;
						unset($i, $temp);
						# # # //
					}

					# # # Inverte todos os valores
					else {
						$result['done']['query'] = array_values($result['done']['query']);
						for ($i=0; $i < count($result['done']['query']); $i++) { 							
							$temp[$result['done']['query'][$i]] = '';
						}
						$result['done']['query'] = $temp;
						unset($i, $temp);
					}
				}
			}
		}

		return $result;
	}
?>
