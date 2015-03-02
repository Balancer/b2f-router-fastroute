<?php

namespace B2\Router;

class Fastroute
{
	var $dispatcher;

	// Вызывается при composer update/require/install
	// Обновляет привязку роутинга пакетов
	public static function adds($routes)
	{
		foreach($routes as $r)
		{
 			// "GET /_b2f/sp/{target_url} b2f_site_preview::make_link"
			// "GET /_cg/b2fsp/[0-9]{4}-[0-9]{2}/{hash}\.png b2f_site_preview"

			list($method, $pattern, $callback) = preg_split('/\s+/', $r);
			\B2\Composer\Cache::appendData('router/fastroute/rules', "\t\$r->addRoute('{$method}', '$pattern', '$callback');");
		}

		\B2\Composer\Cache::addAutoload('router/fastroute/rules', "\$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector \$r) {\n"
			. join("\n", \B2\Composer\Cache::getData('router/fastroute/rules'))
			. "\n});\n\n"
			. "B2\\Router\\Fastroute::setDispatcher(\$dispatcher);\n"
		);
	}

	public static function setDispatcher($dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	// Вызывается при каждом поиске обработчика ссылки
	public static function dispatch($method, $url)
	{
		$routeInfo = $this->dispatcher->dispatch($method, $uri);
		var_dump($routeInfo);
	}
}
