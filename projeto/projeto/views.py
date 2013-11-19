#!/usr/bin/env python
# -*- coding: utf-8 -*-

from pyramid.view import view_config
from .models import Cidadao, Atividade_cidadao, Atividade_orcamento, Dados_site, Midia_comentario, Midia_video
#from .models import Cidadao, UsrTree, Atividade_cidadao
#from .models import Cidadao, MyModel, UsrTree
#por que MyModel?
from beaker.middleware import SessionMiddleware
from datetime import datetime
import warnings
import itertools
from BTrees.OOBTree import OOBTree
import tweepy

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
    record_to_appstruct,
    FormCadastrar,
    FormConfigurar,
    FormContato,
    FormLogin,	
    FormMapa,
    FormInserirP,
    FormOrcamento,
    FormRecadSenha,	
    FormRSenha,
    FormPesqMapa,
    FormOrcFoto,
    FormOrcVideo,
    FormSeguirAtv,
)
import deform
import transaction

@view_config(route_name='inicial', renderer='inicial.slim')
def my_view(request):
    """ 
    Página inicial: resgata os contadores estatísticos e outros dados do site
    """
    #del request.db["atualAtv"]
    if not "dadosSite" in request.db:
        request.db["dadosSite"] = Dados_site()
		
    atualiz_atv = request.db['dadosSite'].atualiz_atv
    qtde_atv_orc = request.db['dadosSite'].qtde_atv_orc
    qtde_atv_usr = request.db['dadosSite'].qtde_atv_usr
    qtde_usr = request.db['dadosSite'].qtde_usr
    
    qtde_fotos = request.db['dadosSite'].qtde_fotos
    qtde_videos = request.db['dadosSite'].qtde_videos
    qtde_coment = request.db['dadosSite'].qtde_coment
    destaque_atv = request.db['dadosSite'].destaque_atv
	
    return {
        'atualAtv': atualiz_atv,
        'qtdeAtvOrc': qtde_atv_orc,
        'qtdeAtvUsr': qtde_atv_usr,
        'qtdeUsr': qtde_usr,
        'qtdeFotos': qtde_fotos,
        'qtdeVideos': qtde_videos,
        'qtdeComent': qtde_coment,
        'destaqueAtv': destaque_atv,
    }

@view_config(
    route_name='lista',
    renderer='lista.slim',
    permission='comum'
)
def lista(request):
    """ 
    Página teste dos cadastros
    """
    cidadaos = request.db['usrTree'].values()
    atividades = request.db['atvTree'].values()
    return {
        'cidadaos': cidadaos,
        'atividades': atividades,
    }
	
@view_config(route_name='cadastro', renderer='cadastro.slim')
def cadastro(request):
    """Cadastro de usuário"""
	# soh eh rodado 1 vez... tem que colocar na configurcao ou coisa assim?...
    # Ensure that a ’userdb’ key is present
    # in the root
    if not request.db.has_key("usrTree"):
        request.db["usrTree"] = OOBTree()
		
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
		#tchau lista
        #request.db['cidadaos'][cidadao.email] = cidadao
        request.db['usrTree'][cidadao.email] = cidadao
		
        dadosSite = request.db['dadosSite']
        #chama função para atualizar contadores
        Dados_site.addUsr(dadosSite)
		
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

    cidadao = Cidadao("","")
    cidadao = request.db["usrTree"][authenticated_userid(request)]	
	#verificar se cidadão está preenchido
	
    esquema = FormConfigurar().bind(request=request)
    esquema.title = "Configuração de usuário"	
    form = deform.Form(esquema, buttons=('Salvar', 'Excluir'))

    if 'Salvar' in request.POST:
        # Validação do formulário
        try:
            appstruct = form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
        
        cidadao = merge_session_with_post(cidadao, request.POST.items())
        transaction.commit()		
        return HTTPFound(location=request.route_url('usuario'))
    elif 'Excluir' in request.POST:
        del request.db["usrTree"][authenticated_userid(request)]
        transaction.commit()
        headers = forget(request)
        return HTTPFound(location=request.route_url('inicial'))		
    else:
        # Apresentação do formulário
        appstruct = record_to_appstruct(cidadao)
        return{'form':form.render(appstruct=appstruct)}	
		
@view_config(route_name='contato', renderer='contato.slim')
def contato(request):
    """Contato"""
    # Import smtplib for the actual sending function
    import smtplib
	
    esquema = FormContato().bind(request=request)
    esquema.title = "Entre em contato com o Cuidando"
    form = deform.Form(esquema, buttons=('Enviar',))
    if 'Enviar' in request.POST:
        # Validação do formulário
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        sender = request.POST.get("email")
        receivers = ['silvailziane@yahoo.com.br']	
        message = request.POST.get("assunto")		
        						
        try:
            #s = smtplib.SMTP( [host [, port [, local_hostname]]] )
            s = smtplib.SMTP('pop.mail.yahoo.com.br',587)
            smtpObj.sendmail(sender, receivers, message)	
            s.quit()	        
            print "Successfully sent email"
		#except SMTPException:
        except:
            print "Error: unable to send email"		
      
		
        return HTTPFound(location=request.route_url('inicial'))
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
    """ 
    Página para login, site, face e twitter

	#autorização básica
    auth = tweepy.BasicAuthHandler(username, password)
	
	#autorização OAuth
    auth = tweepy.OAuthHandler(consumer_token, consumer_secret) #token e secret da aplicação ->pegar no twitter
	#se estiver utilizando um callback url
    #auth = tweepy.OAuthHandler(consumer_token, consumer_secret, callback_url)

	#busca o token de requisição
    try:
        redirect_url = auth.get_authorization_url()
    except tweepy.TweepError:
        print 'Error! Failed to get request token.'

	#armazenar tokens na sessão - pseudo exemplo
	#So now we can redirect the user to the URL returned to us earlier from the get_authorization_url() method.
    session.set('request_token', (auth.request_token.key, auth.request_token.secret)

    # Example using callback (web app)
    verifier = request.GET.get('oauth_verifier')

    # Example w/o callback (desktop)
    verifier = raw_input('Verifier:')
	
    token = session.get('request_token')
    session.delete('request_token')
    auth.set_request_token(token[0], token[1])

    try:
        auth.get_access_token(verifier)
    except tweepy.TweepError:
        print 'Error! Failed to get access token.'	
	
	#guardar estes valores no banco
    auth.access_token.key
    auth.access_token.secret	
	
	#quando gardar, utilizar apenas isto para utilizar o twitter
    auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(key, secret)	
	
    twitterApi = tweepy.API(auth) 
    """
	
    esquema = FormLogin().bind(request=request)
    esquema.title = "Login"
	#botoes nao aceitam frases como label = "esqueci a senha"
    #form = deform.Form(esquema, buttons=('Entrar', 'Esqueci a senha'))
    form = deform.Form(esquema, buttons=('Entrar', 'Esqueci'))
    if authenticated_userid(request):
       request.session.flash(u"Usuário já está logado, caso queira entrar com usuário diferente, faça o logout.")	
       return HTTPFound(location=request.route_url('usuario'))	   
    if 'Entrar' in request.POST:
        try:
            form.validate(request.POST.items())		
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        email = request.POST.get("email")
        senha = request.POST.get("senha")
        if email in request.db["usrTree"]:
            cidadao = Cidadao("","")
            cidadao = request.db["usrTree"][email]
            if cidadao.senha == senha:
                headers = remember(request, email)
                next = request.route_url('usuario')
                request.session.flash(u"Usuário logado")				
                return HTTPFound(location=next, headers=headers)					
            else:
                request.session.flash(u"Email ou senha inválidos")			
        else:
            request.session.flash(u"Email ou senha inválidos")		
        return {'form': form.render()}
    #não entra nesse elif
	#elif 'Esqueci a senha' in request.POST:  
    elif 'Esqueci' in request.POST:  
        return HTTPFound(location=request.route_url('r_senha'))
    else:
        return {'form': form.render()}
    
@view_config(route_name='usuario', renderer='usuario.slim', permission='basica')
def usuario(request):
    """
	Página do perfil do usuário
	"""
    cidadao = Cidadao("","")
    cidadao = request.db["usrTree"][authenticated_userid(request)]	
    return {
        'cidadao': cidadao
    }

@view_config(route_name='sobre', renderer='sobre.slim')
def sobre(request):
    """ 
    Página sobre
    """
    return {}

@view_config(route_name='mapa', renderer='mapa.slim')
def mapa(request):
    """
    Página dos orçamentos mapeados
    """
    esquemaPesq = FormPesqMapa().bind(request=request)
    esquemaPesq.title = "Pesquisa"
    formPesq = deform.Form(esquemaPesq, buttons=('Pesquisar',))	
    
    esquema = FormMapa().bind(request=request)
    esquema.title = "Mapa"
	#legenda do botão - inserir ponto
    form = deform.Form(esquema, buttons=('Inserir',))

	
    if 'Pesquisar' in request.POST:
        try:
            formPesq.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        return HTTPFound(location=request.route_url('lista'))
    elif 'Inserir' in request.POST:
        return HTTPFound(location=request.route_url('inserir_ponto'))	
    else:

        # values passed to template for rendering
        return {
            'form':form.render(),
            'formPesq':formPesq.render(),
            'showmenu':True,
            }

@view_config(route_name='orcamento', renderer='orcamento.slim')
def orcamento(request):
    """
    Página de um orçamento individual
    """	
    esquemaFoto = FormOrcFoto().bind(request=request)
    esquemaFoto.title = "Foto"
    formFoto = deform.Form(esquemaFoto, buttons=('UploadF',))	

    esquemaVideo = FormOrcVideo().bind(request=request)
    esquemaVideo.title = "Video"
    formVideo = deform.Form(esquemaVideo, buttons=('UploadV',))		
	
    esquemaSeguir = FormSeguirAtv().bind(request=request)
    esquemaSeguir.title = "Seguir atualizações"
    formSeguir = deform.Form(esquemaSeguir, buttons=('Salvar',))
	
    esquema = FormOrcamento().bind(request=request)
    #esquema.title = "Comentários"
    form = deform.Form(esquema, buttons=('Enviar',))	
	
    esquemaResp = FormOrcamento().bind(request=request)
    #esquema.title = "Comentários"
    formResp = deform.Form(esquemaResp, buttons=('Responder',))	
	
    #atv_orc = Atividade_orcamento("","")
    atv_orc = Atividade_cidadao("","")
    atv_orc = request.db["atvTree"]["TesteComent2"]		
	#atividade vinda do mapa
    #atv_orc = request.db["orcTree"]
	#atv_orc = request.db["atvTree"]

    #esquema para colocar o id nos forms das respostas
    # envia para o template uma lista com forms de resposta	
    i = 0
    formsResps = []	
    for coment in atv_orc.midia_coment:
        formResp = deform.Form(esquemaResp, buttons=('Responder',), formid=str(i))
        formsResps.append(formResp.render())		
        i = i + 1		 
	
    cidadao = Cidadao("","")
    cidadao = request.db["usrTree"][authenticated_userid(request)]		
	
    if 'UploadF' in request.POST:
        try:
            formFoto.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
			
		#3 linhas abaixo se repetindo para os 3 forms.... como otimizar??
        dadosSite = request.db['dadosSite']
        #chama função para inserir na lista de atualizações		
        Dados_site.addAtual(dadosSite, atv_orc) 
        Dados_site.addFoto(dadosSite)
        transaction.commit() 		
        return HTTPFound(location=request.route_url('orcamento'))	
    elif 'UploadV' in request.POST:
        try:
            formVideo.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
		
		#colocar dentro de try catch		
	    #3 linhas abaixo se repetindo para os 3 forms.... como otimizar??
        dadosSite = request.db['dadosSite']
        #chama função para inserir na lista de atualizações		
        Dados_site.addAtual(dadosSite, atv_orc) 
        Dados_site.addVideo(dadosSite)			
		
        video = Midia_video("")
		#bolar alguma validação de lnk?
        video.linkOrig = request.POST.get('video')
		
        #colocar essas funções no model		
        video.link = video.linkOrig.replace('.com/','.com/embed/')		
        video.link = video.linkOrig.replace('watch?v=','embed/')				
        video.data = datetime.now()	
        video.cidadao = authenticated_userid(request)	
		
        Atividade_cidadao.addVideo(atv_orc, video)		
        transaction.commit()				
		
        return HTTPFound(location=request.route_url('orcamento'))		
    elif 'Enviar' in request.POST:
        try:
            print "não validando form de comentário"		
			#não funcionaaaaa por que a validação dá errado????
            #form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            print "form de comentário deu erro"			
            return {'form': e.render()}				
		#3 linhas abaixo se repetindo para os 3 forms.... como otimizar??
		
        dadosSite = request.db['dadosSite']
        #chama função para inserir na lista de atualizações		
        Dados_site.addAtual(dadosSite, atv_orc)
        Dados_site.addComent(dadosSite)	

        coment = Midia_comentario("", "")
        coment.comentario = request.POST.get('comentario')
        coment.data = datetime.now()	
        coment.cidadao = authenticated_userid(request)	
        coment.atividade = "teste"
        transaction.commit()		
		#verificar como vai ficar com a atividade orçamentária

        Atividade_cidadao.addComent(atv_orc, coment)	
        transaction.commit()	
        return HTTPFound(location=request.route_url('orcamento'))	
    elif 'Responder' in request.POST:
        try:
            print "não validando form de resposta de comentário"	
			#não funcionaaaaa por que a validação dá errado????
            #formResp.validate(request.POST.items())
        except deform.ValidationFailure as e:			
            return {'form': e.render()}			
		#pega o id do form que enviou a resposta do comentário
        posted_formid = int(request.POST['__formid__'])		
        		
		#3 linhas abaixo se repetindo para os 3 forms.... como otimizar??
        dadosSite = request.db['dadosSite']
        #chama função para inserir na lista de atualizações		
        Dados_site.addAtual(dadosSite, atv_orc)
        Dados_site.addComent(dadosSite)	

		#como faço para amarrar a resposta no objeto pai?...
        coment = Midia_comentario("", "")
        coment.comentario = request.POST.get('comentario')
        coment.data = datetime.now()	
        coment.cidadao = authenticated_userid(request)
        transaction.commit()		

		#adiciona a resposta ao comentário pai, conforme o id do form de resposta
        comentPai = atv_orc.midia_coment[posted_formid] 
        comentPai.respostas.append(coment)		
	
        transaction.commit()	
        return HTTPFound(location=request.route_url('orcamento'))			
    elif 'Salvar' in request.POST:
        try:
            formSeguir.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}	
						
        Cidadao.addSeguir(cidadao, atv_orc, request.POST.get('seguir'))	
        transaction.commit()

        return HTTPFound(location=request.route_url('orcamento'))		
    else: 
        seguirAtv = cidadao.pontos_a_seguir	
        #verifica se o usuário logado está seguindo a atividade    	    
        if atv_orc.atividade in seguirAtv:
            appstruct = {'seguir':True,}  
        else:
            appstruct = {'seguir':False,} 
			  		
        appstructOrc = record_to_appstruct(atv_orc)
	
        return {
            'orcamento': atv_orc,		
            'form': form.render(appstruct=appstructOrc),
            'coments': atv_orc.midia_coment,
            'formResp': formsResps,
            'formVideo': formVideo.render(),
            'videos': atv_orc.midia_video,			
            'formFoto': formFoto.render(),
            'formSeguir': formSeguir.render(appstruct=appstruct),				
        }
	
@view_config(route_name='inserir_ponto', renderer='inserir_ponto.slim')
def inserir_ponto(request):
    """ 
    Página para inserir novos pontos/atividades no mapa pelo usuário
    """
    esquema = FormInserirP().bind(request=request)
    esquema.title = "Inserir ponto no mapa"
    form = deform.Form(esquema, buttons=('Inserir', 'Cancelar'))
	
    if not request.db.has_key("atvTree"):
        request.db["atvTree"] = OOBTree()
		
    if 'Inserir' in request.POST:
        try:
            form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}

        if(authenticated_userid(request)):	
		    # Criação e inserção	
            atividade = Atividade_cidadao("","")
            atividade = merge_session_with_post(atividade, request.POST.items())
		    #inserir id para a atividade?
            atividade.data = datetime.now()
            atividade.cidadao = authenticated_userid(request)
            request.db['atvTree'][atividade.atividade] = atividade
			
            dadosSite = request.db['dadosSite']
            #chama função para inserir na lista de atualizações		
            Dados_site.addAtual(dadosSite, atividade)
            Dados_site.addAtvUsr(dadosSite)
            transaction.commit()
            request.session.flash(u"Atividade de usuário cadastrada com sucesso.")
		#teste	
        return HTTPFound(location=request.route_url('lista'))
    else:
        return {'form': form.render()}

@view_config(route_name='privacidade', renderer='privacidade.slim')
def privacidade(request):
    """ 
    Página com a política de privacidade do site
    """
    return {}
		
@view_config(route_name='termos', renderer='termos.slim')		
def termos(request):
    """ 
    Página com os termos e condições de uso do site
    """
    return {}

@view_config(
    route_name='rcad_senha',
    renderer='rcad_senha.slim',
    permission='basica'
)
def rcad_senha(request):
    """Redefinir senha de usuário"""

    esquema = FormRecadSenha().bind(request=request)
    esquema.title = "Redefinir senha"
    cidadao = Cidadao("","")
    
    form = deform.Form(esquema, buttons=('Salvar',))
    if 'Salvar' in request.POST:
        # Validação do formulário
        try:
            appstruct = form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
        #validar token, se ok, merge session
        cidadao = merge_session_with_post(cidadao, request.POST.items())
        transaction.commit()		
        return HTTPFound(location=request.route_url('usuario'))
    else:
        return{'form':form.render()}
		
@view_config(
    route_name='r_senha',
    renderer='r_senha.slim',
    permission='comum'
)
def r_senha(request):
    """ 
    Reconfiguração de senha do usuário
    Envia token para email do usuário
    """
    esquema = FormRSenha().bind(request=request)
    esquema.title = "Reenviar senha"
    
    form = deform.Form(esquema, buttons=('Enviar',))
    if 'Enviar' in request.POST:
        # Validação do formulário
        try:
            appstruct = form.validate(request.POST.items())
        except deform.ValidationFailure as e:
            return {'form': e.render()}
			
        email = request.POST.get("email")

        if email in request.db["usrTree"]:
            #enviar email com token, armazenar esse token
            headers = remember(request, email)
            return HTTPFound(location=request.route_url('rcad_senha'), headers=headers)				
        else:
            warnings.warn("Email ou senha inválidos", DeprecationWarning)
		
        return HTTPFound(location=request.route_url('rcad_senha'))
    else:
        return {'form': form.render()}		