# encoding: utf-8
require "selenium-webdriver"
require 'csv'
require 'fileutils'
t = Time.new
@dir_data = "/var/www/data/"
@dir_script = @dir_data + "#{t.year}-#{t.month}-#{t.day}-#{t.hour}_#{t.min}"

FileUtils::mkdir_p @dir_script

def selecione_diretoria_regional dre
	Selenium::WebDriver::Support::Select.new(@driver.find_element(:id, "cboDRE")).select_by(:index, dre)
end

def selecione_setor setor
	Selenium::WebDriver::Support::Select.new(@driver.find_element(:id, "cboSetor")).select_by(:index, setor)
end

def selecione_faixa_etaria faixa_etaria
	Selenium::WebDriver::Support::Select.new(@driver.find_element(:id, "cboFaixaEtaria")).select_by(:index, faixa_etaria)
end

def clique_confirmar
	@driver.find_element(:id, "btnConfirmar").click
end

def tamanho_da_fila
	fila = 0
	begin
		fila = @driver.find_element(:class, "gerencialRelatTexto1").text
		fila["Total de candidatos com os Parâmetros Informados = "] = ""					
	rescue
	end
	fila
end

def scrap_data_from_website
	t = Time.new
	@driver = Selenium::WebDriver.for :firefox
	@driver.navigate.to "http://eolgerenciamento.prefeitura.sp.gov.br/se1426g/frmgerencial/ConsultaCandidatosCadastrados.aspx?Cod=000000"

	t = Time.new
	file_name = "#{@dir_script}/creches_sp_#{t.year}_#{t.month}_#{t.day}.csv"
	header = ["DRE", "setor / Setor", "Faixa Nascimento", "Tamanho da fila"]

	n_diretorias = 13
	n_faixas_etarias = 6
	diretorias_regionais = @driver.find_element(:id, "cboDRE").text.split("\n")
	faixas_etarias = @driver.find_element(:id, "cboFaixaEtaria").text.split("\n")
	setores = []

	CSV.open(file_name, "wb") do |csv|
		csv << header
		(1..n_diretorias).each do |dre|
			selecione_diretoria_regional(dre)
			setores = @driver.find_element(:id, "cboSetor").text.split("\n")
			setor = 1
			tem_setor = true
			while tem_setor					
				begin
					selecione_setor(setor)
					(1..n_faixas_etarias).each do |faixa_etaria|
						selecione_faixa_etaria faixa_etaria
						clique_confirmar
						fila = tamanho_da_fila
						csv << [diretorias_regionais[dre], setores[setor], faixas_etarias[faixa_etaria], fila]
					end
				rescue
					tem_setor = false	
				end
				setor = setor + 1
			end
		end
	end
	@driver.quit
	return file_name
end

def clean_data(file_name)
	t = Time.new
	csv_file = "#{file_name}"
	csv_clean = "#{@dir_script}/clean_#{t.year}_#{t.month}_#{t.day}.csv"

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

	CSV.foreach(csv_file) do |csv|
		lines << csv
	end

	#remove cabecalho
	lines.delete_at 0

	#process
	hashdist = {}
	last_dist = nil
	dist = nil

	lines.each do |l|
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

def associa_populacao(cleaned_file, dados_populacao)
	t = Time.new
	fila = {}
	populacao = {}
	file_final = "#{@dir_script}/filas.csv" 
	CSV.foreach(cleaned_file) do |row|
		distrito = row[0]
		f = row[1]

		fila[distrito] = f 
	end

	CSV.foreach(dados_populacao) do |row|
		distrito = row[0]
		p = row[1]

		populacao[distrito] = p 
	end

	#write
	CSV.open(file_final, "wb") do |csv|
		csv << ["Distrito", "Indice per capta (*100)", "Tamanho da fila", "Populacao"]
		i = 1
		fila.each do |k, v|
			csv << [k, (v.to_f / populacao[k].to_f) * 100, v.to_f, populacao[k].to_f] if i!= 1
			i += 1
		end
	end
	return file_final
end

#1
file_name = scrap_data_from_website
#2
cleaned_file_name = clean_data(file_name)
#3
file_final = associa_populacao(cleaned_file_name, @dir_data + "populacao_0_4_anos.csv")
#4
FileUtils::rm "#{@dir_data}/filas.csv", :force => true
FileUtils::cp file_final, @dir_data