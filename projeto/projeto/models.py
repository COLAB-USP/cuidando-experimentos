from persistent.mapping import PersistentMapping
from persistent import Persistent


class MyModel(PersistentMapping):
    __parent__ = __name__ = None


class Cidadao(Persistent):

    def __init__(
        self,
        nome,
        senha,
        nome_completo="",
        genero="",
        nascimento="",
        email="",
        rua="",
        bairro="",
        cidade="",
        estado="",
        foto="",
        informacoes="",
        login_twitter="",
        login_facebook="",
    ):

        self.nome = nome
        self.senha = senha

        self.nome_completo = nome_completo
        self.genero = genero
        self.nascimento = nascimento
        self.email = email
        self.rua = rua
        self.bairro = bairro
        self.cidade = cidade
        self.estado = estado
        self.foto = foto
        self.informacoes = informacoes
        self.login_twitter = login_twitter
        self.login_facebook = login_facebook

        self.notificacoes_site = []
        self.notificacoes_email = []
        self.atualizacoes_pontos = []
        self.atualizacoes_eventos = []

class Notificacao(Persistent):

    def __init__(
        self,
        atividade,        
    ):

        self.atividade = atividade

class Atividade(Persistent):

    def __init__(
        self,
        atividade,
        descricao,

    ):

        self.atividade = atividade
        self.descricao = descricao

class Atividade_cidadao(Persistent):

    def __init__(
        self,
        cidadao,
        atividade,
        data,
        tipo,

    ):
        self.cidadao = cidadao
        self.atividade = atividade
        self.data = data
        self.tipo = tipo        

class Atividade_orcamento(Persistent):

    def __init__(
        self,
        atividade,
        orcado,
        atualizado,
        ano,
        empenhado,
        liquidado,
        orgao,

    ):

        self.atividade = atividade
        self.orcado = orcado
        self.atualizado = atualizado    
        self.ano = ano
        self.empenhado = empenhado
        self.liquidado = liquidado    
        self.orgao = orgao

class Midia(Persistent):

    def __init__(
        self,
        atividade,
        data,
        cidadao,

    ):

        self.atividade = atividade
        self.data = data
        self.cidadao = cidadao    

class Midia_foto(Persistent):

    def __init__(
        self,
        imagem,
        data,

    ):

        self.imagem = imagem
        self.data = data        

class Midia_video(Persistent):

    def __init__(
        self,
        link,
        data,

    ):

        self.imagem = imagem
        self.data = data            

class Midia_comentario(Persistent):

    def __init__(
        self,
        comentario,
        data,
        comentarioPai,

    ):

        self.comentario = comentario
        self.data = data    
        self.comentarioPai = comentarioPai        

class Denuncia(Persistent):

    def __init__(
        self,
        midia,
        descricao,
        atividade,

    ):
        self.midia = midia
        self.descricao = descricao
        self.atividade = atividade    
        
def appmaker(zodb_root):
    if not 'app_root' in zodb_root:
        app_root = MyModel()
        zodb_root['app_root'] = app_root
        import transaction
        transaction.commit()
    return zodb_root['app_root']
