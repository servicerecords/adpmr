<x-date-field label="Date they joined"
              field="serviceperson-enlisted-date"
              :mandatory="false"></x-date-field>

@error('serviceperson-discharged-date-year')
    @if($message === 'Date of death is before 1 Jan 1920')
        <x-details :label="$message">
            No records held where death is before 1 Jan 1920. Contact <a href="https://www.nationalarchives.gov.uk/">The National Archive</a>.
        </x-details>
    @endif
@enderror

<x-date-field
    :label="session('serviceperson-died-in-service', \App\Models\Constant::NO) === \App\Models\Constant::NO ? 'Date they left' : 'Date of death in service'"
    field="serviceperson-discharged-date"
    :mandatory="false"></x-date-field>
<x-text-area label="Further information"
             field="serviceperson-discharged-information"
             hint="For example Ranks, Grades, Regiments, National Insurance number."
             :mandatory="false"
             :character-limit="200"></x-text-area>
