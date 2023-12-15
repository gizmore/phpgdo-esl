<?php
namespace GDO\EdwardSnowdenLand\Method;

use GDO\Comments\Comments_List;
use GDO\Core\GDO_ArgError;
use GDO\EdwardSnowdenLand\ESL_Rule;
use GDO\EdwardSnowdenLand\ESL_RuleComments;

final class RuleComments extends Comments_List
{

    public function gdoCommentsTable()
    {
        return ESL_RuleComments::table();
    }

    /**
     * @throws GDO_ArgError
     */
    protected function getRule(): ESL_Rule
    {
        return $this->gdoParameterValue('id');
    }

    /**
     * @throws GDO_ArgError
     */
    public function getMethodDescription(): string
    {
        return sprintf('%s - %s - %s - %s',
            t('module_esl'),
            t('md_esl_rule'),
            $this->getMethodTitle(),
            $this->getRule()->getMetaDescr(),
        );
    }

    public function hrefAdd()
    {
        return href('EdwardSnowdenLand', 'RuleAdd');
    }
}

