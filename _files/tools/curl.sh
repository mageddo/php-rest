# cadastrar um anuncio
curl http://www.reunimotors.com.br/ws/anuncio-cadastrar -F "veiculo_imagem_1=@images/two.png" -F "rsq=`cat mocks/cadastrar-1.js`"

# alterar um anuncio
curl http://www.reunimotors.com.br/ws/anuncio-alterar -F "veiculo_imagem_1=@images/one.png" -F "rsq=`cat mocks/alterar-1.js`"
 
# listar um anuncio
curl http://www.reunimotors.com.br/ws/anuncio-listar -F "rsq=`cat mocks/listar-1.js`"

# deletar um anuncio
curl http://www.reunimotors.com.br/ws/anuncio-deletar -F "rsq=`cat mocks/deletar-1.js`"
