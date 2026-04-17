@php
    $role = $roleTitle ?? $employee?->designation?->title;
@endphp
<x-layouts.document-print pageTitle="Offer letter" :backUrl="route('documents.offer-letters.index')">
    @include('documents.partials.letterhead', ['company' => $company, 'title' => 'Offer Letter'])

    <div class="doc-body">
        <p class="doc-meta">
            {{ $letter->issued_date?->format('j-F-Y') }}<br>
        </p>

        <p>Dear {{ $employee?->gender == 'M' ? 'Mr.' : 'Mrs.'}} {{ Str::title($employee?->full_name) ?? 'Candidate' }}
        </p>

        <p>
            With reference to your application and the interviews you had with <strong>{{ $company['name'] }} </strong>,
            we are pleased to offer you employment in our company on the following terms and conditions.

        <table id="terms_conditions">
            <tr>
                <td>1.Designation</td>
                <td>:</td>
                <td>{{ $employee?->designation?->title }}</td>
            </tr>
            <tr>
                <td>2.Department</td>
                <td>:</td>
                <td>{{ $employee?->department?->name }}</td>
            </tr>
            <tr>
                <td>3.Date Of Joining</td>
                <td>:</td>
                <td>{{ $employee?->joining_date->format('d-m-y') }} ( {{ $employee?->joining_date->format('l') }})</td>
            </tr>
            <tr>
                <td>4.Compensation</td>
                <td>:</td>
                <td>Rs {{ $annex['total_ctc'] / 12  }} per month + retirals</td>
            </tr>
            <tr>
                <td>5:Probation</td>
                <td>:</td>
                <td>First six months from the date of joining will be treated as probation period.
                    During this period, no increments will apply</td>
            </tr>
            <tr>
                <td>6.Confirmation</td>
                <td>:</td>
                <td>After completion of six months, we will evaluate your performance and decide whether to retain your
                    services. Unless the employment is confirmed in writing at the end of the probation period, it
                    should be considered terminated. </td>
            </tr>
            <tr>
                <td>7.House Of work</td>
                <td>:</td>
                <td>9.00am to 6.15pm (with weekly off as per company policy)</td>
            </tr>
            <tr>
                <td>8.Notice Of termination</td>
                <td>:</td>
                <td>During the probation period, your service can be terminated by either side by giving two day’s
                    written notice. Upon confirmation, one month’s written notice is required from either side. If you
                    are already on an assignment and if your presence in the assignment is necessary as assessed by the
                    management, the management
                    reserves the right to require you to work till the assignment is complete.</td>
            </tr>
            <tr>
                <td>9.Leave Policy</td>
                <td>:</td>
                <td>As per the rules of the company, you can avail 6 days casual & 6 days sick leave per year.</td>
            </tr>
        </table>
        <!-- @include('documents.partials.letterfooter', ['company' => $company]) -->
        <!-- <div class="page-break"></div> -->
        <p>Please sign and return the copy of this letter in token of your acceptance, if the terms and conditions
            specified above and enclosed are acceptable to you.</p>

        <p>We welcome you to Magneto Dynamics and look forward to your contribution to the success and growth of the
            Company For Magneto Dynamics</p>
        <!-- signature of director -->
        <div class="doc-signature">
            <img src="{{ asset('images/director_sign.png') }}" alt="" width="100" height="50">
            <p class="doc-signature__name">{{ $company['director'] }}</p>
        </div>
        <P>I agree to the above terms and conditions and will be joining on: </P>
        <div class="doc-signature">

            <div class="emp_signature">
                <p class="doc-signature__name">[ {{ $employee?->full_name }}]</p>
                <div class="confirm_date_of_joining">
                    <span>confirmed Date Of Joining</span>
                    <br>
                    <span>{{ $employee?->joining_date->format('d-m-y') }}</span>
                </div>
            </div>

        </div>
        @include('documents.partials.letterfooter', ['company' => $company])
        <div class="page-break"></div>

        <!-- page 2 -->
        @include('documents.partials.letterhead', ['company' => $company, 'title' => 'Offer Letter'])
        <div class="salary_breakup_div">
            <section>
                <table>
                    <tr>
                        <th colspan="3"><strong>SALARY BREAKUP</strong></th>
                    </tr>
                    <tr>
                        <th><strong>S.No</strong></th>
                        <th><strong>PARTICULARS</strong></th>
                        <th><strong>Value</strong></th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>Salary</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>BASIC</td>
                        <td>{{ $annex['basic'] }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>HRA</td>
                        <td>{{ $annex['hra'] }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Conveyance</td>
                        <td>{{ $annex['conveyance'] }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Vehicle Maintenance</td>
                        <td>{{ $annex['vehicle_maintenance'] }}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Production incentive</td>
                        <td>{{ $annex['production_incentive'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>Gross Pay</strong></td>
                        <td><strong>{{ $annex['gross_pay'] }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><strong>Benefits</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>PF+ESI</td>
                        <td>{{ $annex['pf_esi'] }}</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td><strong>Total Cost to Company</strong></td>
                        <td><strong>{{ $annex['total_ctc']}}</strong></td>
                    </tr>
                </table>

                <p>
                    <strong>Note :</strong>
                </p>
                <ol>
                    <li>All payments are subject to Tax deduction at source (TDS). You are responsible for declaring
                        your tax
                        exemptions & tax liabilities</li>
                    <li>Take home pay will be Gross Pay - Applicable Statutory deductions(PF, ESI, Professional Tax
                        etc.)</li>
                    <li>All reimbursements are at actuals and need to be supported with bills/vouchers whenever
                        available</li>
                </ol>
            </section>
            
        </div>
        @include('documents.partials.letterfooter', ['company' => $company])
    </div>


</x-layouts.document-print>