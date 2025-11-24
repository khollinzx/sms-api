<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sms:send-scheduled-sms')->everyTenMinutes();

