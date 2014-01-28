<?php
$str = <<<EOF
        <Task>
            <UID>{$data['i']}</UID>
            <ID>{$data['i']}</ID>
            <Name>{$data['title']}</Name>
            <Type>0</Type>
            <IsNull>0</IsNull>
            <CreateDate>2013-01-10T10:53:33</CreateDate>
            <WBS>{$data['outline']}</WBS>
            <OutlineNumber>{$data['outline']}</OutlineNumber>
            <OutlineLevel>{$data['indent']}</OutlineLevel>
            <Priority>500</Priority>
            <Start>2011-12-07T16:00:00</Start>
            <Finish>2011-12-07T17:00:00</Finish>
            <ActualStart>2011-12-07T16:00:00</ActualStart>
            <Work>PT1H0M0S</Work>
            <RegularWork>PT1H0M0S</RegularWork>
            <ActualWork>PT0H0M0S</ActualWork>
            <RemainingWork>PT1H0M0S</RemainingWork>
            <Duration>PT1H0M0S</Duration>
            <DurationFormat>7</DurationFormat>
            <ResumeValid>0</ResumeValid>
            <EffortDriven>1</EffortDriven>
            <Recurring>0</Recurring>
            <OverAllocated>0</OverAllocated>
            <Estimated>0</Estimated>
            <Milestone>0</Milestone>
            <Summary>0</Summary>
            <Critical>1</Critical>
            <IsSubproject>0</IsSubproject>
            <IsSubprojectReadOnly>0</IsSubprojectReadOnly>
            <ExternalTask>0</ExternalTask>
            <Cost>0</Cost>
            <FixedCost>0</FixedCost>
            <FixedCostAccrual>3</FixedCostAccrual>
            <PercentComplete>0</PercentComplete>
            <PercentWorkComplete>0</PercentWorkComplete>
            <ConstraintType>0</ConstraintType>
            <CalendarUID>-1</CalendarUID>
        </Task>

EOF;

return $str;