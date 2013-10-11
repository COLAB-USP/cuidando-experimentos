#!/usr/bin/env python
# -*- coding: utf-8 -*-

from pyramid.view import view_config
from .models import Cidadao, MyModel
from beaker.middleware import SessionMiddleware

from pyramid.httpexceptions import (
    HTTPFound,
    HTTPNotFound,
    #HTTPForbidden,
)
from pyramid.security import (
    remember,
    forget,
    authenticated_userid,
)
from forms import (
    merge_session_with_post,
    FormCadastrar,
    FormConfigurar,
    FormContato,
    FormSobre,
    FormUsuario,
    FormLogin,	
    FormMapa,
    FormInserirP,
    FormOrcamento,	
)
import deform
import transaction

def simple_app(environ, start_response):
    # Get the session object from the environ
    session = environ['beaker.session']

    # Check to see if a value is in the session
    user = 'logged_in' in session

    # Set some other session variable
    session['user_id'] = 10

    start_response('200 OK', [('Content-type', 'text/plain')])
    return ['User is logged in: %s' % user]

@view_config(route_name='inicial', renderer='inicial.slim')
def my_view(request):
    session = request.session
    session['name'] = 'Teste1' 
    session.save()
    return {'project': 'projeto'}

@view_config(
    route_name='lista',
    renderer='lista.slim',
    permission='comum'
)
def lista(request):
    cidadaos = request.db['cidadaos'].values()
    return {
        'cidadaos': cidadaos,
    }
	
@view_config(route_name='cadastro', renderer='cadastro.slim')
def cadastro(request):
    """Cadastro de usuário"""

    esquema = FormCadastrar().bind(request=request)
    esquema.title = "Cadastrar novo usuário"
    form = deform.Form(esquema, buttons=('Cadastrar',))
    if 'Cadastrar' in request.POST:
        # Validação do formulário
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
			
		# Criação e inserção	
        cidadao = Cidadao("","")
        cidadao = merge_session_with_post(cidadao, request.POST.items())
        request.db['cidadaos'][cidadao.email] = cidadao
        #request.db.commit()
        transaction.commit()
        request.session.flash(u"Usuário registrado com sucesso.")
        request.session.flash(u"Agora você já pode logar com ele.")
        return HTTPFound(location=request.route_url('lista'))
    else:
        # Apresentação do formulário
        return {'form': form.render()}

@view_config(
    route_name='configuracao',
    renderer='configuracao.slim',
    permission='basica'
)
def configuracao(request):
    """Configuração de usuário"""

    esquema = FormConfigurar().bind(request=request)

    esquema.title = "Configuração de usuário"
    form = deform.Form(esquema, buttons=('Salvar', 'Excluir conta'))
    if 'Configurar' in request.POST:
        # Validação do formulário
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
        
        # Atualizar registro - usuário logado  ??		
        #cidadao = Cidadao("","")
        #cidadao = merge_session_with_post(cidadao, request.POST.items())
        #request.db[cidadao.nome] = cidadao
        #request.db.commit()
        #transaction.commit()
        #request.session.flash(u"Usuário registrado com sucesso.")
        #request.session.flash(u"Agora você já pode logar com ele.")
        return HTTPFound(location=request.route_url('lista'))
    else:
        # Apresentação do formulário
        return {'form': form.render()}		
		
@view_config(route_name='contato', renderer='contato.slim')
def contato(request):
    """Contato"""

    esquema = FormContato().bind(request=request)
    esquema.title = "Entre em contato com o Cuidando"
    form = deform.Form(esquema, buttons=('Enviar',))
    if 'Contato' in request.POST:
        # Validação do formulário
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        # Apresentação do formulário
        return {'form': form.render()}

@view_config(route_name='logout', permission='basica')
def logout(request):
    """Página para logout"""
    headers = forget(request)
    request.session.flash(u"Você foi deslogado.")
    #request.session.pop_flash()
    return HTTPFound(location=request.route_url('inicial'), headers=headers)


@view_config(route_name='login', renderer='login.slim')
def login(request):

    esquema = FormLogin().bind(request=request)
    esquema.title = "Login"
    form = deform.Form(esquema, buttons=('Entrar', 'Esqueci a senha'))
    print(request.POST)
    if 'Entrar' in request.POST:
        try:
            form.validate(request.POST.items())
			#request.session[request.POST.items()]
            #login = request.POST.get('login')
            #password = request.POST.get('password')			
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        email = request.POST.get("email")
        headers = remember(request, email)
        print("LOGADO!!!!!!!!!!!!!!", email)

        next = request.route_url('lista')
        return HTTPFound(location=next, headers=headers)
    else:
        return {'form': form.render()}
    
@view_config(route_name='usuario', renderer='usuario.slim')
def usuario(request):

    esquema = FormUsuario().bind(request=request)
    esquema.title = "Página do usuário"
    form = deform.Form(esquema)
    if 'Usuario' in request.POST:

        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}

@view_config(route_name='sobre', renderer='sobre.slim')
def sobre(request):

    esquema = FormSobre().bind(request=request)
    esquema.title = "Sobre o Cuidando"
    form = deform.Form(esquema)
    if 'Sobre' in request.POST:

        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}

@view_config(route_name='mapa', renderer='mapa.slim')
def mapa(request):

    esquema = FormMapa().bind(request=request)
    esquema.title = "Mapa de orçamentos"
    form = deform.Form(esquema, buttons=('Inserir ponto',))
    if 'Mapa' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()} 		

@view_config(route_name='orcamento', renderer='orcamento.slim')
def orcamento(request):

    esquema = FormOrcamento().bind(request=request)
    esquema.title = "Detalhes do orçamento"
    form = deform.Form(esquema, buttons=('Enviar',))
    if 'Orcamento' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}
	
@view_config(route_name='inserir_ponto', renderer='inserir_ponto.slim')
def inserir_ponto(request):

    esquema = FormInserirP().bind(request=request)
    esquema.title = "Inserir ponto no mapa"
    form = deform.Form(esquema, buttons=('Inserir', 'Cancelar'))
    if 'inserir_ponto' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}

@view_config(route_name='privacidade', renderer='privacidade.slim')
def privacidade(request):

    esquema = FormInserirP().bind(request=request)
    esquema.title = "Inserir ponto no mapa"
    form = deform.Form(esquema)
    if 'privacidade' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}
		
@view_config(route_name='termos', renderer='termos.slim')		
def termos(request):

    esquema = FormInserirP().bind(request=request)
    esquema.title = "Inserir ponto no mapa"
    form = deform.Form(esquema)
    if 'privacidade' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}
