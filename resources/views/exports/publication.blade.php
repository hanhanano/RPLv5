<table>
    <thead>
        <tr>
            <th>Publication</th>
            <th>Plan Type</th>
            <th>Plan Name</th>
            <th>Plan Start</th>
            <th>Plan End</th>
            <th>Plan Desc</th>
            <th>Final Start</th>
            <th>Final End</th>
            <th>Final Desc</th>
            <th>Next Step</th>
            <th>Struggle</th>
            <th>Solution</th>
        </tr>
    </thead>
    <tbody>
        @foreach($publication->stepsplans as $plan)
            @php $final = $plan->stepsFinals; @endphp

            @if($final && $final->struggles->count())
                @foreach($final->struggles as $s)
                    <tr>
                        <td>{{ $publication->publication_name }}</td>
                        <td>{{ $plan->plan_type }}</td>
                        <td>{{ $plan->plan_name }}</td>
                        <td>{{ $plan->plan_start_date }}</td>
                        <td>{{ $plan->plan_end_date }}</td>
                        <td>{{ $plan->plan_desc }}</td>
                        <td>{{ $final->actual_started }}</td>
                        <td>{{ $final->actual_ended }}</td>
                        <td>{{ $final->final_desc }}</td>
                        <td>{{ $final->next_step }}</td>
                        <td>{{ $s->struggle_desc }}</td>
                        <td>{{ $s->solution_desc }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $publication->publication_name }}</td>
                    <td>{{ $plan->plan_type }}</td>
                    <td>{{ $plan->plan_name }}</td>
                    <td>{{ $plan->plan_start_date }}</td>
                    <td>{{ $plan->plan_end_date }}</td>
                    <td>{{ $plan->plan_desc }}</td>
                    <td>{{ $final->actual_started ?? '' }}</td>
                    <td>{{ $final->actual_ended ?? '' }}</td>
                    <td>{{ $final->final_desc ?? '' }}</td>
                    <td>{{ $final->next_step ?? '' }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
