## sincronizador


/root/comunica.sh /boot/config.cfg compress=false


##--> Definir o script_agendado que ira enviar sincronizar com o webservice baseado no arquivo --comunica.sh


until ["http://webservicedozerodois.com.br:8090/$pvid/$user/$senha/add&&.asp"]                        ##verifica se existem serviços adicionados no webservice
do
	echo "host_sem_parametros"
	sleep 60
done

echo "rasp parametrizado"
script_exec=1;
while [script_exec]                                                                                      ## loop infinito de verifição de serviços para o rasp
do 
	until ["http://webservicedozerodois.com.br:8090/$pvid/$user/$senha/newdates&&.asp"]
	do
		echo "sem_serviços"
		sleep 60
	done

	consult_script="http://webservicedozerodois.com.br:8090/$pvid/$user/$senha/rotina_new_script.asp"                           ## executando script
	
	mkdir -p /root/$consult_script/
	wget /root/$consult_script/ http://webservicedozerodois.com.br:8090/$pvid/$user/$senha/download/$consult_script/script.sh
	/root/$consult_script/script.sh
	"http://webservicedozerodois.com.br:8090/$pvid/$user/$senha/limpa_script.asp"                                               ## coloca zero na variavel valida
done
