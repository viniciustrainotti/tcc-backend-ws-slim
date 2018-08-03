## --> Definir o script_agendado que ira sincronizar novos raspbarry;

## Serão adicionados os usuários , senhas e pvid no sistema
## tudo isso será sincronizado , e serão chamados os comandos de acordo com cada item
## Serão adicionados no sistema o raspbarry e dentro dele será chamado o comando 
	
	pvid=["consulta_ao_banco_para_o_pvid_baseado_no_pvid_estabelecido_no_sistema"]	
	mkir -p /equipamentos/$pvid/
	
## Serão vinculados os usuários aos raspbarry
	
	pvid=["consulta_ao_banco_para_o_pvid_baseado_no_pvid_estabelecido_no_sistema"]
	user=["consulta_ao_banco_para_o_user_baseado_no_pvid_estabelecido_no_sistema"]
	senha=["consulta_ao_banco_para_o_senha_baseado_no_pvid_estabelecido_no_sistema"]
	mkir -p /equipamentos/$pvid/$user/$senha/
	touch /equipamentos/$pvid/$user/$senha/valido
	echo 1 >> /equipamentos/$pvid/$user/$senha/valido
