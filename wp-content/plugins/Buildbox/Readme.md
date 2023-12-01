# Desafio Buildbox wordpress

Neste projeto utilizei,
* Boilerplate criado pelo Devin Vinson no repositório https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
* Utilizado bootstrap css na parte admin e no frontend.
* $wpdb como conexão para o banco de dados.
* Meta_dados para salvar as informações dos Likes/Dislikes.
* Jquery e Ajax em todas as requisições 

Em qualquer página ou post, pode ser incluído o ShortCode ([buildbox-top-liked]) e o plugin irá ser executado.

------------
Organização dos Arquivos
------------
    Buildbox
    ├── admin
    │   ├── css
    │   │   └── buildbox-admin.css       
    │   │       └── css da parte admin.
    │   │
    │   ├── js  
    │   │   └── buildbox-admin.js        
    │   │       └── JS com todas as requisições da parte admin.
    │   │
    │   ├── partials
    │   │   └── buildbox-admin-settings.php
    │   │       └── Classe que cria o front end admin.
    │   │
    │   ├── class-buildbox-admin.php     
    │   │   └── Classe que contem todo o código da parte admin.
    │   │        
    │   └── index.php   
    │           
    ├── includes
    │   ├── class-buildbox.php                      
    │   │   └── Classe que regista todos os hooks e registra as principais funcões do plugin,
    │   │       como Ativação, Desativação, Loader, I18n e as funções admin e public.        
    │   │   
    │   ├── class-buildbox-activator.php           
    │   │   └── Classe que contem o código que executa quando o plugin é ativado.
    │   │
    │   ├── class-buildbox-deactivator.php         
    │   │   └── Classe que contem o código que executa quando o plugin é desativado.
    │   │
    │   ├── class-buildbox-i18n.php               
    │   │   └── Classe que contem o código para internacionalização do plugin.
    │   │    
    │   ├── class-buildbox-loader.php
    │   │   └── Classe que contem o código para utilizar alguns hooks padrão do WP.
    │   │
    │   └── index.php               
    │
    ├── languages
    │   └── Pasta destinada as traduções do plugin.
    │
    ├── public
    │   ├── css
    │   │   └── buildbox-public.css    
    │   │       └── css da parte publica (frontend do WP).
    │   │
    │   ├── js
    │   │   └── buildbox-public.js
    │   │       └── JS da parte publica (frontend do WP).
    │   │
    │   ├── class-buildbox-public.php    
    │   │   └── Classe que contem todo o código da parte admin.        
    │   │       
    │   ├── class-buildbox-report-shortcode.php    
    │   │   └── Classe que contem todo o código do shortcode. 
    │   │
    │   └── index.php
    │
    ├── Buildbox.php 
    │   └── Classe principal onde estancia as principais classes do plugin.
    │
    ├── index.php              
    ├── Readme.md
    └── uninstall.php  
        └── Arquivo que executa quando o plugin é desinstalado.                                 
 
------------
## Instalação
```bash
  Instalação e ativação normal de um plugin Wordpress.
  Requer versão do wordpress maior que 5.0
```

## Como Usar
```bash
  Ao instalar o plugin ele irá criar uma opção nova chamada Buildbox like dentro do menu Settings.  
  
  Quando acessado ele mostra a parte admin do plugin, onde tem as opções
  Allowed -> se marcado o plugin começa a funcionar, se não marcado não exibe os icones de like e deslike. 
  Post Types -> Mostra os Post Types que estarão funcionando o plugin.  
    
  Para utilizar o ShortCode, dentro de um Post ou Página basta utilizar o Bloco "ShortCode" padrão do Gutemberg.  
  
  Padrão do ShortCode - [buildbox-top-liked]
```

