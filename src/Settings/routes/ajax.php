<?php

/**
* Rota dos arquivos AJAX
*/

# Ajax
$app->group('/ajax', function () {
	$this->map(['POST'], '/busca-canal',	App\Controllers\AjaxController::class .':getChannelsById');
	$this->map(['POST'], '/busca-parceiro',	App\Controllers\AjaxController::class .':getPartnersById');
	$this->map(['POST'], '/encurta-url',	App\Controllers\AjaxController::class .':shortUrl');		
});