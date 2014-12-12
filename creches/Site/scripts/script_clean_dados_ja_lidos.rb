def clean_data(file_name)
	t = Time.new
	csv_file = "#{file_name}"
	csv_clean = "#{@dir_script}/clean_manual_08_12.csv"

	dicionario = {
	'AGUA RASA' => 'ÁGUA RASA',
	'GRAJAU' => 'GRAJAÚ',
	'CAPAO REDONDO' => 'CAPÃO REDONDO',
	'VILA JACUI'=>'VILA JACUÍ',
	'VILA CURUCA' => 'VILA CURUÇÁ',
	'VILA SONIA' => 'VILA SÔNIA',
	'SAO MIGUEL' => 'SÃO MIGUEL',
	'SAO MATEUS' => 'SÃO MATEUS',
	'JOSE BONIFACIO' => 'JOSÉ BONIFÁCIO',
	'SAO DOMINGOS' => 'SÃO DOMINGOS',
	'SAO LUCAS' => 'SÃO LUCAS',
	'CANGAIBA' => 'CANGAÍBA',
	'CIDADE LIDER' => 'CIDADE LÍDER',
	'CARRAO' => 'CARRÃO',
	'TREMEMBE' =>'TREMEMBÉ',
	'JACANA' => 'JAÇANÃ',
	'TATUAPE' => 'TATUAPÉ',
	'BELEM' => 'BELÉM',
	'JARDIM ANGELA' => 'JARDIM ÂNGELA',
	'JARDIM SAO LUIS' => 'JARDIM SÃO LUÍS',
	'BRAS' => 'BRÁS',
	'BRASILANDIA' => 'BRASILÂNDIA',
	'SANTA CECILIA' => 'SANTA CECÍLIA',
	'CONSOLACAO' => 'CONSOLAÇÃO',
	'SACOMA' => 'SACOMÃ',
	'SAO RAFAEL' => 'SÃO RAFAEL',
	'SE' => 'SÉ',
	'REPUBLICA' => 'REPÚBLICA',
	'BUTANTA' => 'BUTANTÃ',
	'JAGUARE' => 'JAGUARÉ',
	'JARAGUA' => 'JARAGUÁ',
	'SAUDE' => 'SAÚDE',
	'FREGUESIA DO O' => 'FREGUESIA DO Ó',
	'LIMAO' => 'LIMÃO'
	}


	lines = []
	cc = 1
	CSV.foreach(csv_file) do |csv|
		lines << csv if cc <= 3	
		cc = cc % 6
	end

	#remove cabecalho
	lines.delete_at 0

	#process
	hashdist = {}
	last_dist = nil
	dist = nil
	
	line_i = 0
	#lines.each do |l|
	while line_i < lines.size
		l = line_i
		#l[1] = setor e #l[3] = quantidade
		l[1] = l[1].slice(0..(l[1].index('/') - 2)) 
		if(l[1] != last_dist)
			dist = l[1]
			if dicionario.has_key?(dist) 
				dist = dicionario[dist].upcase 
			end
			hashdist[dist] = l[3].to_i
			#puts "hashdist[l[1]] = l[3].to_i => #{l[3].to_i} "
			last_dist = l[1]
			#puts "last_dist = l[1] => #{l[1]}"
		else
			hashdist[dist] += l[3].to_i
		end
		if(line_i % 2 == 0)
			line_i += 4
		else
			line_i += 1		
		end
	end

	#write
	CSV.open(csv_clean, "wb") do |csv|
		csv << ["Distrito", "Tamanho da fila"]
		hashdist.each do |dist, fila|
			csv << [dist, fila]
		end
	end
	return csv_clean
end

clean_data('/var/www/cuidando2/creches/Site/scripts/data/2014-12-8-6_0/creches_sp_2014_12_8.csv')
