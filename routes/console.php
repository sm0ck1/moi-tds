<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:delete-old-data')->hourly();
