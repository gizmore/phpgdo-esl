<?php
namespace GDO\EdwardSnowdenLand;

use GDO\Core\GDT_Enum;
use GDO\Core\WithGDO;
use GDO\Net\GDT_Url;
use GDO\UI\GDT_Icon;
use GDO\UI\GDT_Link;

/**
 *
 */
final class GDT_ESLPetitionState extends GDT_Enum
{
    use WithGDO;

    const NOT_YET = 'not_yet';
    const CREATED = 'created';
    const VOTED = 'voted';
    const PUBLISHED = 'published';
    const SUCCEEDED = 'succeeded';
    const FAILED = 'failed';

    protected function __construct()
    {
        parent::__construct();
        $this->enumValues(self::NOT_YET, self::CREATED, self::VOTED, self::PUBLISHED, self::SUCCEEDED, self::FAILED);
        $this->initial(self::NOT_YET);
        $this->notNull();
        $this->icon('question');
    }

    public function gdtDefaultLabel(): ?string
    {
        return 'esl_petition_state';
    }

    public function getRule(): ESL_Rule
    {
        return $this->gdo;
    }

    public function renderCell(): string
    {
        $rule = $this->getRule();
        $icon = null;
        $tt = null;
        switch ($this->getVar())
        {
            case self::NOT_YET:
                $icon = 'question';
                $tt = 'tt_eslps_new';
                break;
            case self::CREATED:
                $icon = 'create';
                $tt = 'tt_eslps_created';
                break;
            case self::VOTED:
                $icon = 'vote';
                $tt = 'tt_eslps_voted';
                break;
            case self::PUBLISHED:
                $icon = 'trophy';
                $tt = 'tt_eslps_published';
                return GDT_Link::make()->icon($icon)->href($rule->getURL())->tooltip($tt)->textNone()->render();
            case self::SUCCEEDED:
                $icon = 'thumbs_up';
                $tt = 'tt_eslps_succeeded';
                break;
            case self::FAILED:
                $icon = 'thumbs_down';
                $tt = 'tt_eslps_failed';
                break;
        }
        return GDT_Icon::make()->icon($icon)->tooltip($tt)->render();
    }

}
