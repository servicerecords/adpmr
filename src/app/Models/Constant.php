<?php

namespace App\Models;


class Constant
{
    public const YES = 'Yes';
    public const NO = 'No';
    public const UNKNOWN = 'Don\'t know';
    public const DATE_PLACEHOLDER = '—';

    public const DAY_PLACEHOLDER = 'DD';
    public const MONTH_PLACEHOLDER = 'MM';
    public const YEAR_PLACEHOLDER = 'YYYY';

    public const DAY_ZERO_PLACEHOLDER = '00';
    public const MONTH_ZERO_PLACEHOLDER = '00';
    public const YEAR_ZERO_PLACEHOLDER = '0000';

    public const RELATION_UNRELATED = 'I am not related';
    public const RELATION_SPOUSE = 'I am their spouse or civil partner';
    public const RELATION_CHILD = 'I am their child';
    public const RELATION_GRANDCHILD = 'I am their grandchild';
    public const RELATION_PARENT = 'I am their parent';
    public const RELATION_SIBLING = 'I am their sibling';
    public const RELATION_NIECE_NEPHEW = 'I am their niece or nephew';
    public const RELATION_GRANDPARENT = 'I am their grandparent';
    public const RELATION_OTHER = 'Other';

    public const SECTION_SERVICE = 1;
    public const SECTION_DIED_IN_SERVICE = 2;
    public const SECTION_ESSENTIAL_INFO = 4;
    public const SECTION_SERVICEPERSON_DETAILS = 8;
    public const SECTION_DEATH_CERTIFICATE = 16;
    public const SECTION_APPLICANT_DETAILS = 32;
    public const SECTION_APPLICANT_RELATIONSHIP = 64;
    public const SECTION_APPLICANT_NEXT_OF_KIN = 128;
    public const SECTION_CHECK_ANSWERS = 256;

    public const SERVICEPERSION = 'serviceperson';
    public const APPLICANT = 'applicant';
}
