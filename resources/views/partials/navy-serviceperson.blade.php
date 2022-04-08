@if(in_array('Service record not held', $errors->all()))
    <x-details :label="'Service record not held'">
        No records are held where the serviceperson joined before {{ session('service', \App\Models\ServiceBranch::NAVY) ? '1926' : '' }}. Contact <a href="https://www.nationalarchives.gov.uk/">The National Archive</a>.
    </x-details>
@endif
<x-date-field label="Date they joined"
              field="serviceperson-enlisted-date"
              :mandatory="false"></x-date-field>
<x-date-field
    :label="session('serviceperson-died-in-service', \App\Models\Constant::NO) === \App\Models\Constant::NO ? 'Date they left' : 'Date of death in service'"
    field="serviceperson-discharged-date"
    :mandatory="false"></x-date-field>
<x-text-area label="Further information"
             field="serviceperson-discharged-information"
             hint="For example Ranks, Grades, Regiments, National Insurance number."
             :mandatory="false"
             :character-limit="200"></x-text-area>
