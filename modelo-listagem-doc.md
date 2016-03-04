>> v1
# request É a tag principal, nela serão colocadas todas as informações da solicitação
	- type é identificador que informa se a solicitação será uma listagem("list") ou cadastro de anúncios("cad")
	- client-key é a chave que autentica a solicitação tanto para listar como para cadastrar
# ads é o container de todos os anúncios que irão ser listados
# ad é o anúncio propriamente dito do qual terá todas as informações do anúncio para cadastro caso a tela estiver no modo cadastro, caso a tela esteja no modo listagem poderá ser passado um anúncio no container de anúncios(ou o primeiro será pego caso seja passado mais de um anúncio) do qual a finalidade é efetuar a busca de anúncios com base no preenchimento dos parametros
	- 

>> v2
# request tag container 
# config é a tag de configuração da requisição, nela podem ser definidos:
	- type pode receber [list,cad]
		- list identifica que o XML está em modo de busca de anúncios
		- cad identifica que o XML está em modo de cadastro de anúncios
	- client-key chave que autêntica a requisição
# ads container dos anúncios, contém os anúncios em modo listagem e é o container dos filtros em modo cadastro
# ad é o anúncio propriamente dito do qual virá um ou mais em modo listagem com suas respectivas propriedades, ou pode-se setar um para realizar o filtro de busca de anúncios com base no anúncio passado, caso seja passado mais  de um anúncio será considerado apenas o primeiro
	