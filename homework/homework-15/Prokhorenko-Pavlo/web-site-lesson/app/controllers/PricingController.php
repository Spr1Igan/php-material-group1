<?php

final class PricingController extends AbstractController
{
   

    public function get() {
        $content = loadView(__DIR__."/../../app/views/pricing.php");
        $r = new Response($content);
        $r->setTitle("pricing");     
        return $r;
    }
   
}