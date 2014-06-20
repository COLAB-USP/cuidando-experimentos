Cuidando 2.0
=========

Novo Cuidando do meu Bairro.


### INSTALAÇÃO

Instale usando Python 2. Acho que não está funcionando no 3. Dependendo da sua distro de Linux tera que usar easy_install2 e virtualenv2 nos comandos abaixo.

Comandos para distro que use apt-get:

	sudo apt-get install build-essential easy_install
	sudo easy_install virtualenv
	virtualenv env
	source env/bin/activate
	cd projeto
	python setup.py develop
	pserve development.ini --reload

Abrir http://0.0.0.0:5000 no navegador.
