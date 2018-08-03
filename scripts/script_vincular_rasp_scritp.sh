## assim que identificar que existe um novo raspbarry, definir o script que ira adicionar o equipamento ao banco de dados
## via sistemas serão amarrados, scripts , rasp, parametros e servicos

# montando o script
pvid=["consulta_ao_banco_para_o_pvid_baseado_no_pvid_estabelecido_no_sistema"]
parametro=["consulta_ao_banco_para_o_paramentro_baseado_no_pvid_estabelecido_no_sistema"]
destin=["consulta_ao_banco_para_o_destin_baseado_no_pvid_estabelecido_no_sistema"]
conteudo_script=["consulta_aobanco_parao_conteudo_congelado_script"]
numero_script=["consulta_aobanco_parao_conteudo_congelado_script"]

echo 1 >> /equipamentos/$pvid/$user/$senha/new_script
echo numero_script >> /equipamentos/$pvid/$user/$senha/validar_script
echo "pvid=$pvid" >> /equipamentos/$pvid/$user/$senha/download/$numero_script/script.sh
echo "parametro=$parametro" >> /equipamentos/$pvid/$user/$senha/download/$numero_script/script.sh
echo "destin=$destin" >> /equipamentos/$pvid/$user/$senha/download/$numero_script/script.sh
echo $conteudo_script >> /equipamentos/$pvid/$user/$senha/download/$numero_script/script.sh

