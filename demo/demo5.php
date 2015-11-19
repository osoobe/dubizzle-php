<?php
require_once "../vendor/autoload.php";

use Dubizzle\ModelCar;

$car = new ModelCar();
$url = "https://dubai.dubizzle.com/motors/used-cars/acura/mdx/2015/11/16/2002-acura-mdx-4x4-2/?back=ZHViYWkuZHViaXp6bGUuY29tL21vdG9ycy91c2VkLWNhcnMvYWN1cmEvP3ByaWNlX19ndGU9JnByaWNlX19sdGU9JnllYXJfX2d0ZT0meWVhcl9fbHRlPTIwMTYma2lsb21ldGVyc19fZ3RlPSZraWxvbWV0ZXJzX19sdGU9JnNlbGxlcl90eXBlPSZrZXl3b3Jkcz0maXNfYmFzaWNfc2VhcmNoX3dpZGdldD0wJmlzX3NlYXJjaD0xJnBsYWNlc19faWRfX2luPVN0YXJ0K3R5cGluZytoZXJlJnBsYWNlc19faWRfX2luPSZhZGRlZF9fZ3RlPSZhdXRvX2FnZW50PQ%3D%3D&pos=0";
$car->fetch_page($url);
?>