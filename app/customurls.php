<?php
function base_url($param=NULL)
{
	return Config::get('site.base_url').$param;
}
function app_url($param=NULL)
{
	return Config::get('site.app_url').$param;
}
?>