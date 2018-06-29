<?php
switch (APPLICATION_ENV) {
    /**
    * Configuração em Desenvolvimento
    **/	
    case 'development': 

    define('SITE_URL', 'http://localhost/novos-projetos/projeto-plataforma/portal-de-operacoes/public');
    define('GDU_HOSTNAME', 'http://localhost/novos-projetos/projeto-plataforma/gerenciador-de-usuarios/api/v1');
    define('GDU_TOKEN', 'Basic cm9vdDp0b29y');

    break;
    
    /**
    * Configuração em Homologação
    **/
    case 'homologation': 

    break;

    /**
    * Configuração em Produção
    **/
    case 'production':

    break;
}