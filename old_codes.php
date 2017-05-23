/* App/Http/Controllers/LoanApplicationController@save

        //=======================SAVE THIS FOR SOME OTHER TIME...====================================
        //Declare an empty array that'll place the computed payment periods
        $payment_periods = array();

        //Loop through each payment period to count for the total payment
        foreach ($paymentPeriod as $dt) {
            $payment_periods[] = $dt->format('M j, Y');
            $paymentPeriod_count++;
        }

        $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);

        //Starting Month and Year for your payment since the loan was disbursed
        $paymentStartDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //Ending Month and year for your payment since the loan was disbursed
        $paymentEndDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_term->loan_payment_term_no_of_months . 'months' .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //payment interval based on given payment schedule
        $paymentInterval = DateInterval::createFromDateString($payment_schedule->payment_schedule_days_interval . ' days');

        //*IMPORTANT* Compute the payment schedules from start to finish
        $paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);

        $paymentPeriod_count = 0;

        //Declare an empty array that'll place the computed payment periods
        $payment_periods = array();

        //Loop through each payment period to count for the total payment
        foreach ($paymentPeriod as $dt) {
            $payment_periods[] = $dt->format('M j, Y');
            $paymentPeriod_count++;
        }

        $periodicRate = $totalLoan / $paymentPeriod_count;
        */