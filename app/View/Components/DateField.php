<?php

namespace App\View\Components;

class DateField extends FormField
{
    /**
     * @var string
     */
    public $dayLabel = 'Day';

    /**
     * @var string
     */
    public $monthLabel = 'Month';

    /**
     * @var string
     */
    public $yearLabel = 'Year';

    /**
     * @var bool
     */
    public $hideDay = false;

    /**
     * @var bool
     */
    public $hideMonth = false;

    /**
     * @var bool
     */
    public $hideYear = false;

    /**
     * @var bool
     */
    public $hideDayLabel = false;

    /**
     * @var bool
     */
    public $hideMonthLabel = false;

    /**
     * @var bool
     */
    public $hideYearLabel = false;

    /**
     * @var bool
     */
    public $singleField = false;

    /**
     * @var bool
     */
    public $period = 'year';

    /**
     * Create a new component instance.
     *
     * @param null $field
     * @param string $label
     * @param null $value
     * @param bool $hint
     * @param null $selected
     * @param array $options
     * @param null $labelExtra
     * @param bool $mandatory
     * @param int|false $characterLimit
     * @param bool $fullWidth
     * @param bool $hideDay
     * @param bool $hideMonth
     * @param bool $hideYear
     * @param string $dayLabel
     * @param string $monthLabel
     * @param string $yearLabel
     * @param bool $hideDayLabel
     * @param bool $hideMonthLabel
     * @param bool $hideYearLabel
     * @param bool $singleField
     * @param bool $period
     */
    public function __construct($field = null, $label = 'Option', $value = null, $hint = false, $selected = null,
                                $options = [], $labelExtra = null, $mandatory = true, $characterLimit = false,
                                $fullWidth = false, $autocomplete = false, $hideDay = false, $hideMonth = false, $hideYear = false,
                                $dayLabel = 'Day', $monthLabel = 'Month', $yearLabel = 'Year',
                                $hideDayLabel = false, $hideMonthLabel = false, $hideYearLabel = false, $singleField = false, $period = 'year')
    {
        parent::__construct($field, $label, $value, $hint, $selected, $options,
            $labelExtra, $mandatory, $characterLimit, $fullWidth, $autocomplete);

        $this->dayLabel = $dayLabel;
        $this->monthLabel = $monthLabel;
        $this->yearLabel = $yearLabel;
        $this->hideDay = $hideDay;
        $this->hideMonth = $hideMonth;
        $this->hideYear = $hideYear;
        $this->hideDayLabel = $hideDayLabel;
        $this->hideMonthLabel = $hideMonthLabel;
        $this->hideYearLabel = $hideYearLabel;
        $this->singleField = $singleField;
        $this->period = $period;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.date-field');
    }
}
