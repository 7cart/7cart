<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class Helper
{

    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function findRequestedTranslation($data)
    {
        $title = '';
        if (!is_array($data)) {
            return $title;
        }

        $lang = $this->requestStack->getCurrentRequest()->headers->get('accept-language');
        if (array_key_exists($lang, $data)) {
            $title = $data[$lang];
        } else if (array_values($data)){
            $title = array_values($data)[0];
        }

        return $title;
    }
}

