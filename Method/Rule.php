<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Core\GDT;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\Table\MethodQueryCard;

final class Rule extends MethodQueryCard
{

    public function gdoTable()
    {
        return ESL_Rule::table();
    }

    public function getRule(): ESL_Rule
    {
        return $this->getQueryCard();
    }

    public function getMethodTitle(): string
    {
        return $this->getRule()->getTitle();
    }

    public function getMethodDescription(): string
    {
        return sprintf('%s - %s - %s - %s',
            t('module_esl'),
            t('md_esl_rule'),
            $this->getMethodTitle(),
            $this->getRule()->getMetaDescr(),
        );
    }

    public function execute(): GDT
    {
        $response = parent::execute();



        return $response;
    }


}
