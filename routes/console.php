<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('delete:old-data')->hourly();
